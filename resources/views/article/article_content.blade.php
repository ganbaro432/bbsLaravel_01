@extends('layouts.app')
@section('sectionTitle')
{{$content->title}}
@endsection
@section('sectionMetatag')
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{$content->title}}">
<meta property="og:site_name" content="{{$content->title}}">
<meta name="description" content="コメント。">
<meta name="twitter:description" content="コメント。">
{{-- 独自CSS --}}
<link rel="stylesheet" href="{{ asset('css/article.css?20220912') }}">
@endsection

@section('content')

    <!--メインカラムの大枠-->
    <div id="article_body" class="p-4">
        {{-- 公開フラグがtrueなら表示 --}}
        @if($content->open_flg == 1)
        {{-- title --}}
        <div class="text-xl md:text-2xl font-bold text-gray-800 py-3">
            {{$content->title}}
        </div>
        
        {{-- 情報 --}}
        <div class="flex justify-between mb-5 ">
            <div class="mr-3 text-sm text-gray-500">
                {{$content->created_at->format('y/m/d H:i')}}
                @if(isset($content->categorynm)) 
                <span class="bg-sky-100 text-xs text-gray-500 rounded px-2 border-solid border border-sky-300">{{$content->categorynm}}</span>
                @endif
            </div>
            
            {{-- snsボタン --}}
            <div class="text-2xl">
                <a href="https://twitter.com/share?url={{url()->current()}}&text={{$content->title}}" rel="nofollow" target="_blank"><i class="fa-brands fa-twitter-square text-blue-500"></i></a>

                <a href="https://social-plugins.line.me/lineit/share?url={{url()->current()}}" rel="nofollow" target="_blank"><i class="fa-brands fa-line text-green-500"></i></a>     
                
            </div>
        </div>
        {{-- 本文 --}}
        <div id="article_content" class="leading-loose">
            {!! $content->body !!}
        </div>




        {{-- 情報 --}}
        <div class="flex justify-between my-5 ">
            <div class="text-2xl">
                <a href="https://twitter.com/share?url={{url()->current()}}&text={{$content->title}}" rel="nofollow" target="_blank"><i class="fa-brands fa-twitter-square text-blue-500"></i></a>

                <a href="https://social-plugins.line.me/lineit/share?url={{url()->current()}}" rel="nofollow" target="_blank"><i class="fa-brands fa-line text-green-500"></i></a>                    
            </div>

            
            <div class="text-xs text-gray-500">
                投稿者:{{$content->name}}
            </div>
        </div>        
        <a href="{{ url('/article/index')}}" class="mt-3 inline-block px-2 py-1 text-orange-500 border border-orange-500 font-semibold rounded hover:bg-orange-100">記事一覧へ</a>
        {{-- 編集 --}}
        <a href="{{ route('article.checkpage', ['id'=>$content->id])}}" class="mt-3 inline-block px-2 py-1 text-orange-500 border border-orange-500 font-semibold rounded hover:bg-orange-100">編集</a>
        @endif
    </div>

<!--メインカラム-->

@endsection

<!--ここだけのサイド-->
@section('sides')

{{-- サイド最新コメント --}}
<div class="w-auto h-auto bg-white">
    <h2 class="p-4 text-base text-black font-bold text-center">最新コメント</h2>
    @if(isset($newcomments))
    @foreach($newcomments as $newcomment)
    
    <div class="newcomment mb-2 p-2 border-b font-light text-sm">
        <a href="{{ route('thread.show', ['id'=>$newcomment->thread->id])}}"><div>{{$newcomment->thread->title}}</div></a>
        {{-- <div class="h-10 truncate whitespace-nowrap overflow-y-hidden">{!! ($newcomment->body) !!}</div> --}}
        <div class="h-10 truncate whitespace-nowrap overflow-y-hidden"><?php echo strip_tags($newcomment->body);  ?></div>
    </div>
    @endforeach
    @endif
</div>



@endsection
