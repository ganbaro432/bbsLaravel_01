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
	   {{-- 独自CSS --}}
	   <link rel="stylesheet" href="{{ asset('css/article.css') }}">
       {{-- css --}}
       <style>
            #imglist img {
                width:100%;
                height: 80%;
            }
        </style>
   </head>	   
	<body class="bg-gray-100">

		<!--メインカラム-->
		<div class="flex justify-center">
			<div id="article_body" class="max-w-7xl md:w-10/12 w-full bg-white mt-1 mb-1 p-5">                    
				<h3 class="text-center">使用していない画像一覧</h3>
                <a href="{{route('member.articleimgdelete')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block m-3">削除</a>
                {{-- 画像一覧 --}}
                <div class="grid grid-cols-4 gap-4">
                @foreach($trash_image as $img)
                <div id="imglist" class="col-span-1 h-28">
                    <img src="{{ asset('storage/'.$img) }}">
                    <p class="text-xs truncate">{{$img}}</p>
                </div>
                @endforeach
                </div>
			</div>
		</div>

	</body>
</html>