<!DOCTYPE html>
<html lang="jp">
   <head prefix="og:http://ogp.me/ns#">
       <meta charset="UTF-8">
       <meta http-equiv="X-UA-Compatible" content="IE=edge">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <meta name="csrf-token" content="{{ csrf_token() }}">  
       <meta name="robots" content="noindex" />      
       <link href="{{ asset('css/app.css?20230710') }}" rel="stylesheet">
       <link rel="stylesheet" href="{{asset('css/all.min.css')}}">
       <link rel="shortcut icon" type="image/x-icon"  href="{{ asset('images/favicon.ico') }}">
       {{-- original --}}
       <link rel="stylesheet" href="{{ asset('css/style.css?20220828') }}">

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

   </head>
       
           <body class="">
               <!--ヘッダー-->
               <header class="bg-white shadow-xs p-4 flex justify-between items-center md:border-b">
                   <div class="">
                    管理ページ
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
                        <form method="post" action="{{ url('vlogout') }}">
                            @csrf 
                        <input type="submit" class="cursor-pointer font-bold" value="ログアウト" />
                        </form>
                       </li>
                     </ul>
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
                        
                           <ul class="w-5/6 hidden absolute z-40 top-2/4 left-1/2 -translate-x-1/2 -translate-y-1/2">
                                <li>
                                     {{-- 検索 --}}
                                    <div class="flex items-center justify-center mb-5">
                                        <form action="{{ route('membersearch.show') }}" method="GET">
                                            <div class="flex border-2 border-gray-200 rounded">       
                                                <input type="text" class="px-1 py-2 w-40" name="keyword" placeholder="削除用検索">
                                                <button type="submit" class="px-2 text-white bg-red-500 border-l "><i class="fa-solid fa-magnifying-glass"></i></button>   
                                            </div>
                                        </form>
                                    </div>
                                </li>
                               <li class="text-center"><a class="text-slate-700 p-5 block tracking-widest font-bold" href="">不具合・要望</a></li> 
                               <li class="text-center"><a class="text-slate-700 p-5 block tracking-widest font-bold" href="">通報リスト</a></li>                         

                               @if(session('simple_auth') == "kanrinin2")
                               <li class="text-center"><a href="{{url('member/article')}}" class="text-slate-700 p-5 block tracking-widest font-bold">記事一覧</a> </li>
                               <li class="text-center"><a href="{{url('member/article/image')}}" class="text-slate-700 p-5 block tracking-widest font-bold">画像一覧</a> </li>
                               <li class="text-center"><a href="{{url('member/list')}}" class="text-slate-700 p-5 block tracking-widest font-bold">リスト</a> </li>
                               @endif

                           </ul>
                       </div>
                   </nav>

               {{-- ヘッダー下 --}}
               <nav class="bg-white flex flex-wrap grid grid-cols-3 md:hidden text-gray-700">
                   <a href="" target="_blank" rel="noopener noreferrer">
                       <div class="py-2 px-2 text-center">
                           <p><i class="fa-solid fa-feather-pointed mr-1"></i></p>
                           <p class="text-sm">ブログ</p>
                       </div>                           
                   </a>

                   <a href="" target="_blank" rel="noopener noreferrer">
                       <div class="py-2 px-2 text-center">
                           <p><i class="fa-brands fa-twitter"></i></p>
                           <p class="text-sm">Twitter</p>
                       </div>                            
                   </a>    
                   <form method="post" action="{{ url('vlogout') }}">
                    @csrf 
                       <div class="py-2 px-2 text-center">
                           <p><i class="fa-solid fa-trash-can mr-1"></i></p>
                           <input type="submit" class="cursor-pointer" value="ログアウト" />
                       </div>                                      
               </nav>   

               <!--大枠-->
               <div class="lg:flex w-full max-w-screen-xl mx-auto md:px-6">
                   <!--サイドカラム大枠-->
                   <div class="fixed inset-0 h-full bg-white z-90 w-full border-b -mb-16 lg:-mb-0 lg:static lg:h-auto lg:overflow-y-visible lg:border-b-0 lg:pt-0 lg:w-1/4 lg:block border-r xl:w-1/5 hidden pt-16 ">
                        <!--サイドカラム個別-->
                        <div class="px-6 pt-6 overflow-y-auto text-base lg:text-sm lg:py-12 lg:pl-6 lg:pr-8 sticky top-0">

                            {{-- 検索 --}}
                            <div class="flex items-center justify-center mb-5">
                                <form action="{{ route('membersearch.show') }}" method="GET">
                                    <div class="flex border-2 border-gray-200 rounded">       
                                        <input type="text" class="px-1 py-2 w-40" name="keyword" placeholder="削除用検索">
                                        <button type="submit" class="px-2 text-white bg-red-500 border-l "><i class="fa-solid fa-magnifying-glass"></i></button>   
                                    </div>
                                </form>
                            </div>

                            <ul>
                                <li class="py-2"><a href="{{url('/member/index')}}">TOP</a> </li>
                                <li class="py-2"><a href="" target="_blank" rel="noopener noreferrer">通報集計表</a> </li>
                                <li class="py-2">
                                    <form method="post" action="{{ url('vlogout') }}">
                                        @csrf 
                                    <input type="submit" class="cursor-pointer" value="ログアウト" />
                                    </form>
                                </li>
                                {{-- --}}
                                @if(session('simple_auth') == "kanrinin2")
                                <li class="py-2"><a href="{{url('member/article')}}">記事一覧</a> </li>
                                <li class="py-2"><a href="{{url('member/article/image')}}">画像一覧</a> </li>
                                <li class="py-2"><a href="{{url('member/list')}}">リスト</a> </li>
                                @endif
                            </ul>
                        </div>
                        <!--サイドの大枠-->
                    </div>

                   <!--メインカラム-->
                   <div class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible bg-white lg:w-3/4 xl:w-4/5">                  
                   @yield('content')
                   </div>
               </div>
               <!--サイドカラム-->
               
               @yield('footer')
               <!--フッター位置高さ調整必要-->
               <footer class="w-full bg-white shadow-xs mt-3 p-4 font-bold text-black">
                   <a href="{{ url('/agreement')}}" class="inline-flex">利用規約</a>
                   <a href="{{ url('/contact')}}">お問い合わせ</a>
                   <a href="" target="blank">要望</a>
               </footer>
       
               {{-- 外部ファイルの置き場 --}}
               @yield('otherfile')
               <script src="{{ asset('/js/hamburger.js?20220712') }}"></script>
           </body>
       </html>