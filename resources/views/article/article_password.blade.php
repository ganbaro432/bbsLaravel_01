<!DOCTYPE html>
<html lang="jp">
    <head prefix="og:http://ogp.me/ns#">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">   
        <meta name="robots" content="noindex" />     
        <link href="{{ asset('css/app.css?20220725') }}" rel="stylesheet">
        <script src="https://kit.fontawesome.com/6ec69d82f2.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" type="image/x-icon"  href="{{ asset('images/favicon.ico') }}">
   </head>	   
	<body class="bg-gray-100">

		<!--メインカラム-->
		<div class="flex justify-center">
			<div id="article_body" class="max-w-7xl md:w-9/12 w-full bg-white mt-1 mb-1 p-5">       
                <p class="text-center mb-2"></p>   
                {{-- エラーがあれば --}}
                @if(session('result'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2" role="alert">
                    <span class="block sm:inline">{{ session('result') }}</span>
                </div>   
                  @endif       
				<h3 class="text-center">確認ページ</h3>
                
                {{-- <form method="post" class="" action="{{ route('member.articleEdit', ['id'=>$article->id])}}"> --}}
                <form method="post" class="" action="{{url('/article/content/edit')}}">         
                    @csrf           
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="mb-6">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">パスワードを入力してください</label>
                        <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="パスワード" required>
                    </div>                      

                    <button id="article-submit" type=”submit” class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">送信</button>
               </form>
			</div>
		</div>

        <script>


      </script>
	</body>
</html>