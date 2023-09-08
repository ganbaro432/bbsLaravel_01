<!DOCTYPE html>
<html lang="jp">
   <head prefix="og:http://ogp.me/ns#">
	   <meta charset="UTF-8">
	   <meta http-equiv="X-UA-Compatible" content="IE=edge">
	   <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	   <meta name="robots" content="noindex" />   
	   <link href="{{ asset('css/app.css?20220709') }}" rel="stylesheet">
	   <script src="https://kit.fontawesome.com/6ec69d82f2.js" crossorigin="anonymous"></script>
	   <link rel="shortcut icon" type="image/x-icon"  href="{{ asset('images/favicon.ico') }}">
   </head>	   
	<body class="bg-gray-100">
		<!--メインカラム-->
		<div class="flex justify-center">
			<div class="max-w-7xl md:w-9/12 w-full bg-white">                    
                <h3 class="text-center">完了</h3>
                <p class="p-5">投稿しました。管理人が確認し、問題なければ公開されます。しばらくお待ちください。</p>

                <p class="text-center"><a href="{{ url('/') }}" >TOPに戻る</a></p>
			</div>
		</div>
	</body>
</html>