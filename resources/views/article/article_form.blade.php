<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>記事投稿</title>
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

      /* load */
      #loading{
        display: table;
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #fff;
        opacity: 0.8;        
      }
      #loading .loadingMsg {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
        padding-top: 140px;
        background: url({{ asset('images/712-88.gif') }}) center center no-repeat;
      }
    </style>

  </head>
  <body>
    <div class="container-fluid">
      @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
      @endforeach
      <a href="{{ url('/')}}">TOPへ戻る</a>
      <h2>記事作成</h2>
      <p>ブラウザの「戻るボタン」を押して当記事作成ページに遷移した場合やエラーから戻された場合、もし画像をアップロードしているのであれば画像アップロードをやり直してください。正しく表示されない恐れがあります。</p>
      <p>1記事につき画像は10枚まで</p>
      <p>画像アップロードは時間がかかりますのでお待ちください</p>
      <a href="{{url('/help')}}" class="py-4 text-gray-700"><div class=""><p>その他記事投稿について解説はこちら</p></div></a>
      <form id="article" method="post" action="" enctype="multipart/form-data">
        @csrf
        <select name="category" class="border p-2 mb-2">
          <option value="0">カテゴリを選択</option>
          <option value="1">1</option>
          <option value="2">2</option>
      </select>
        <input type="text" id="input_title" name="title" placeholder="※タイトルを入力" class="mb-2 w-100 p-2" value="{{ old('title') }}">
        <textarea id="summernote" name="body" value="{!! old('body') !!}"></textarea>
        <input type="text" id="input_name" name="name" class="mt-3" placeholder="※名前" value="{{ old('name') }}"><br>
        <input type="password" name="password" id="password" class="mt-2" placeholder="パスワード">        
        <button class="pbutton btn btn-outline-dark btn-sm" type="button">パスワードを表示</button>
        {{-- パスワード表示実験 --}}
        <div id="areapass" class="text-secondary">
        </div>
        <p>パスワードを入力すると後で修正が可能です</p>
        


        {{-- プレビューをつける --}}
        <button type="button" class="btn btn-primary btn-block mt-5" onclick="onClickPreview(this)">プレビューを見る</button>
        <button type="button" id="article-submit" class="btn btn-warning btn-block" onclick="onClickSave(this)" >投稿する</button>
      </form>

      {{-- 注意事項 --}}
      <div class="mt-5">
        <h4 class="font-weight-bold">投稿前に禁止事項をご確認ください</h4>
        <div class="">
          <dl>
            <dt><span>悪質な内容</span></dt>
            <dd>荒らし行為、わいせつな画像の投稿、犯罪行為に関する投稿など、当掲示板の運営を妨害する行為は禁止しています。</dd>
          </dl>
          <dl>
            <dt><span>誹謗中傷</span></dt>
            <dd>嫌がらせや悪口により他人を著しく傷つける内容は、名誉棄損や侮辱にあたる場合があります。当掲示板では禁止です。</dd>
          </dl>
          <dl>
            <dt><span>権利侵害</span></dt>
            <dd>画像や文章を投稿する前に、「著作権、肖像権、プライバシー」などの他者の権利を侵害していないことを確認してください。</dd>
          </dl>
        </div>
        <div class="">
          <p>これらのルールに違反したユーザーは投稿内容の削除、投稿禁止処分等の制限を受ける可能性があります。権利者から発信者情報開示請求があった場合、法令に則って開示する場合もあり、法的責任が追及されるかもしれません。</p>
          <p>上記全てをご了承いただいた上で投稿してください。</p>
        </div>
      </div> 

    </div>


    


    {{-- summernote_script --}}
    <script>
      jQuery(document).ready(function($) {
      $('#summernote').summernote({
          styleTags: [
          'p',
          { title: '引用', tag: 'blockquote', className: 'blockquote', value: 'blockquote' },
          {title: '見出し', tag: 'h4', className: 'h4', value: 'h4' }
          ],
          toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'paragraph']],
          // ['insert', ['link', 'picture', 'video']],
          ['insert', ['link', 'picture', 'table']],
          // ['view', ['fullscreen', 'codeview', 'help']],
          ['view', ['fullscreen', 'help']],
          ],
          placeholder: '※100文字以上で入力してください',
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

   }).summernote('code', `{!! old('body') !!}`);

   
    function sendFile(file, el) {
      var form_data = new FormData();
      form_data.append('file', file);

      dispLoading("処理中");
    
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

        // 
      }).done(function(response){
          $(el).summernote('editor.insertImage', response.url);
          $("#article").append('<input type="hidden" name="data[]" size="25" value="'+ response.dbpath + '">');
      }).fail(function(xhr, ajaxOptions, thrownError){
          console.log(xhr.responseJSON.message);
          alert(xhr.responseJSON.message);
      }).always(function(){
          removeLoading();
      })

      //     success: function(response) {
      //     $(el).summernote('editor.insertImage', response.url);
      //     // 連結は . では駄目、 +が必要
      //     $("#article").append('<input type="hidden" name="data[]" size="25" value="'+ response.dbpath + '">');
          
      //   },
      //   error: function (xhr, ajaxOptions, thrownError) {
      //     console.log(xhr.responseJSON.message);
      //     alert(xhr.responseJSON.message);
      // }
      // });

    }
  });



    // ボタン分岐
    const onClickPreview = (button) => {
      button.form.action ='/article/preview'
      button.form.target = '_blank'
      button.form.submit()
    }
    const onClickSave = (button) => {
        button.form.action = '/article'
        button.form.target = '_self'
        button.form.submit()
      }


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

    {{-- ロード画面 --}}
    <script>
      function dispLoading(msg){
        if(msg == undefined){
          msg="";
        }
        var dispMsg = "<div class='loadingMsg'>" + msg + "</div>";
        if($("#loading").length == 0){
          $("body").append("<div id='loading'>" + dispMsg + "</div>");
        }
      }

      function removeLoading(){
        $("#loading").remove();
      }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/lang/summernote-ja-JP.js"></script>
  </body>
</html>

