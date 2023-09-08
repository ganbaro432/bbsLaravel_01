 <!DOCTYPE html>
 <html lang="jp">
    <head prefix="og:http://ogp.me/ns#">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">      
        <link href="{{ asset('css/app.css?20220913') }}" rel="stylesheet">
        <meta name="robots" content="noindex" />
        <link rel="stylesheet" href="{{asset('css/all.min.css')}}">
        <link rel="shortcut icon" type="image/x-icon"  href="{{ asset('images/favicon.ico') }}">
        {{-- original --}}
        <link rel="stylesheet" href="{{ asset('css/style.css?202307133') }}">

        <!--ヘッダータイトル、なければ表示-->
        @hasSection('sectionTitle')
        <title>@yield('sectionTitle') | 掲示板</title>
        @else
        <title>掲示板</title>
        @endif

        @hasSection('sectionMetatag')
        @yield('sectionMetatag')
        @else
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="掲示板">
        <meta property="og:site_name" content="掲示板">
        <meta name="description" content="コメント。">
        <meta name="twitter:description" content="コメント。">
        @endif

        {{-- googlesearch --}}
        
    </head>
        
            <body class="bg-slate-100">
               
                
                <!--ヘッダー-->
                <header class="bg-white shadow p-4 ">
                    <div class="max-w-4xl flex justify-between items-center mx-auto">
                        <div class="">
                        {{-- svg --}}
                            <a href="{{ url('/')}}" class="inline-flex font-bold text-black text-lg">
                                HOME
                            </a>
                        </div>
                    {{-- navi --}}
                        <div class="text-gray-700 hidden md:inline-block">
                            <ul class="flex font-bold items-center text-sm">
                                <li class="mr-6">
                                <a class="hover:text-blue-800" href="" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-feather-pointed mr-1"></i>ブログ</a>
                                </li>
                                <li class="mr-6">
                                <a class="hover:text-blue-800" href="{{ url('/contact')}}"><i class="fa-solid fa-envelope mr-1"></i>お問い合わせ</a>
                                </li>
                                <li class="mr-6">
                                <a class="hover:text-blue-800" href="{{url('/recruit')}}"><i class="fa-solid fa-trash-can mr-1"></i>募集</a>
                                </li>
                            </ul>
                        </div> 
                    </div>
                    {{-- ヘッダーに入れたい要素 --}}
                    @yield('header')
                </header>

                    {{-- hamburger --}}
                    <div class="md:hidden openbtn absolute top-3 right-1.5 cursor-pointer w-12 h-12">
                        <span class="inline-block transition-all duration-300 absolute left-1 h-0.5 rounded-sm bg-slate-700 w-3/5 top-3"></span>
                        <span class="inline-block transition-all duration-300 absolute left-1 h-0.5 rounded-sm bg-slate-700 w-3/5 top-5"></span>
                        <span class="inline-block transition-all duration-300 absolute left-1 h-0.5 rounded-sm bg-slate-700 w-3/5 top-7"></span>
                    </div>
                    <nav id="g-nav" class="md:hidden fixed -z-10 opacity-0 top-0 w-full h-screen bg-slate-200 transition-all duration-300">
                        <div id="g-nav-list">
                            <ul class="w-5/6 hidden absolute z-40 top-6 left-1/2 -translate-x-1/2 ">
                                <li class="text-center"><a class="text-slate-700 p-5 block tracking-widest font-bold" href="">通報</a></li> 
                                <li class="text-center"><a class="text-slate-700 p-5 block tracking-widest font-bold" href="">要望</a></li> 
                                {{-- <li class="">
                                    <p class="text-center text-slate-700 pt-5 pb-1 mb-1 block tracking-widest font-bold border-solid ">カテゴリ</p>
                                    <ul class="flex justify-start">
                                        <li class="border-solid border border-gray-600 inline-block my-1"><a class="text-slate-700 p-3 " href="">雑談</a></li>
                                        <li class="border-solid border border-gray-600 inline-block my-1"><a class="text-slate-700 p-3" href="">1</a></li>
                                        <li class="border-solid border border-gray-600 inline-block my-1"><a class="text-slate-700 p-3" href="">2</a></li>
                                    
                                    </ul>
                                </li>  --}}
                            </ul>
                        </div>
                    </nav>

                {{-- ヘッダー下 --}}
                <nav class="bg-white flex flex-wrap grid grid-cols-4 md:hidden text-gray-700">
                    <a href="" target="_blank" rel="noopener noreferrer">
                        <div class="py-2 px-2 text-center">
                            <p><i class="fa-solid fa-feather-pointed mr-1"></i></p>
                            <p class="text-sm">ブログ</p>
                        </div>                           
                    </a>
                    <a href="{{ url('/contact')}}">
                        <div class="py-2 px-2 text-center">
                            <p><i class="fa-solid fa-envelope mr-1"></i></p>
                            <p class="text-sm">問い合わせ</p>
                        </div>                            
                    </a>
                    <a href="" target="_blank" rel="noopener noreferrer">
                        <div class="py-2 px-2 text-center">
                            <p><i class="fa-brands fa-twitter"></i></p>
                            <p class="text-sm">Twitter</p>
                        </div>                            
                    </a>    
                    <a href="{{url('/recruit')}}">
                        <div class="py-2 px-2 text-center">
                            <p><i class="fa-solid fa-trash-can mr-1"></i></p>
                            <p class="text-sm">A</p>
                        </div>                            
                    </a>                                     
                </nav>   

                <!--大枠-->
                <div class="md:flex flex-wrap justify-center lg:px-2 lg:py-5 lg:mt-0 mt-5">
                {{-- ad --}}
                  
                    <!--メインカラム-->
                    <div class="md:max-w-3xl lg:max-w-screen-sm lg:w-7/12 w-full bg-white  mb-1">                    
                    @yield('content')
                    </div>

                    <!--サイドカラム大枠-->
                    <div class="md:max-w-3xl lg:w-72 w-full h-auto lg:mt-0 lg:mb-1 mt-1 mb-1 lg:ml-3">
                        <!--サイドカラム個別-->
                        @yield('side')
                        @yield('sides')


                        
                        <!--サイドの大枠-->
                        <div class="md:sticky md:top-3">
                            <!--サイドのトピック-->
                            <div class="w-auto h-auto bg-white">
                                <h2 class="p-4 m-2 text-base text-black font-bold text-center">サイド固定</h2>
                                <div class="flex justify-center mb-2">
                                    <div class="w-3/4 h-28 bg-gray-200 mb-5 text-center"></div>
                                </div>
                            </div>
        
                        </div>
                        <!--サイドの大枠-->
                    </div>
                </div>
                <!--サイドカラム-->
                
                @yield('footer')
                <!--フッター位置高さ調整必要-->
                <footer class="w-full bg-white shadow mt-3 p-4 font-bold text-black">
                    <a href="{{ url('/agreement')}}" class="inline-flex">利用規約</a>
                    <a href="{{ url('/contact')}}">お問い合わせ</a>
                    <a href="" target="blank">要望</a>
                </footer>
        
                {{-- 外部ファイルの置き場 --}}
                @yield('otherfile')
                <script src="{{ asset('/js/hamburger.js?20220712') }}"></script>
            </body>
        </html>