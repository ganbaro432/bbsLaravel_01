<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex" />
    <title>Summernote with Bootstrap 4</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <style>
      .note-group-image-url {
        display: none;
      }
    </style>

  </head>
  <body>
    <div class="container-md">
      
      @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
      <a href="{{ url('/')}}">TOPへ戻る</a>
      <h2>記事編集</h2>
      <p>ブラウザの「戻るボタン」を押して当記事作成ページに遷移した場合、もし画像をアップロードしているのであれば画像アップロードやり直してください。正しく表示されない恐れがあります。</p>
      <form id="article" method="post" action="{{url('updatearticle')}}" enctype="multipart/form-data">
        @csrf
        <select name="category" class="border p-2 mb-2">
          <option value="0">カテゴリを選択</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
      </select>
        <input type="text" id="input_title" name="title" placeholder="タイトルを入力" class="mb-2 w-100 p-2">
        <textarea id="summernote" name="body"></textarea>
        <input type="hidden" name="id" value="{!! $content->id !!}">
        <input type="text" id="input_name" name="name" class="mt-3" placeholder="名前"><br>


        <button id="article-submit" type=”submit” class="btn btn-danger btn-block mt-5">更新</button>
      </form>
      {{-- 削除 --}}
      <a href="{{ route('article.delete', ['id'=>$content->id])}}">削除する</a>
    </div>


    <div id="url">
    </div>
    


    {{-- summernote_script --}}
    <script>
      jQuery(document).ready(function($) {
      $('#summernote').summernote({
          toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'paragraph']],
          // ['insert', ['link', 'picture', 'video']],
          ['insert', ['link', 'picture', 'video', 'table']],
          // ['view', ['fullscreen', 'codeview', 'help']],
          ['view', ['fullscreen', 'help']],
          ],
          placeholder: '100文字以上で入力してください',
          tabsize: 2,
          height: 500,  
          lang: "ja-JP",
       callbacks: {
        onImageUpload : function(files, editor, welEditable) {
           for(var i = files.length - 1; i >= 0; i--) {
                   sendFile(files[i], this);
            }
        }
       }
   }).summernote('code', `{!! $content->body !!}`);

   
    function sendFile(file, el) {
      var form_data = new FormData();
      form_data.append('file', file);
    
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: form_data,
        type: "POST",
        contentType: 'multipart/form-data',
        // 画像保存用のルート設定
        url: '/article/upload',
        cache: false,
        contentType: false,
        processData: false,
        // success: function(url) {
          success: function(response) {
          
          $(el).summernote('editor.insertImage', response.url);
          // 連結は . では駄目、 +が必要
          // $("#article").append('<input type="hidden" name="data[]" size="25" value="'+ response.url + '">');
          // $("#url").append('<p>' + response.filename + '</p>')
          $("#article").append('<input type="hidden" name="data[]" size="25" value="'+ response.dbpath + '">');
          // console.log(response.path);
          // console.log(response.p);
          
        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log(xhr.responseJSON.message);

          
          alert(xhr.responseJSON.message);
          
      }

      });
    }
  });

    //  元情報を入力
    $('input[name="title"]').val(`{!! $content->title !!}`);
    $('input[name="name"]').val(`{!! $content->name!!}`);

    </script>

    {{-- 未入力ならボタンを押せなく --}}
    <script>
      $(document).ready(function () {

        if($("#input_name").val().length == 0 || $("#input_title").val().length == 0) {
      $('#article-submit').prop('disabled', true);
    }
    $('#input_name, #input_title').on('keydown keyup keypress change', function() {
    if ( $('#input_name').val().length > 0 && $('#input_title').val().length > 0) {
      $('#article-submit').prop('disabled', false);
    } else {
      $('#article-submit').prop('disabled', true);
    }
  });
      });

      // パス表示
      $(function(){
        var input = $('#password');
        var button = $('.pbutton');
        var textarea = $('#areapass');

        button.on('click', function(){
        var inputVal = input.val()+'\n';
        textarea.text(inputVal);
    });
      })      
    </script>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/lang/summernote-ja-JP.js"></script>
  </body>
</html>

