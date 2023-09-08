@extends('layouts.app')
@section('sectionTitle')
{{$header->title}}
@endsection
@section('sectionMetatag')
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{$header->title}}">
<meta property="og:site_name" content="{{$header->title}}">
<meta name="description" content="コメント。">
<meta name="twitter:description" content="コメント。">



{{-- {!! RecaptchaV3::initJs() !!} --}}
@endsection

@section('content')

{{-- @php
dd($comments->max('comment_serial'));
@endphp --}}

<!--アラート-->
@if(session('message'))
<div class="m-4 p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800">
    {{session('message')}}</div>
@endif
@if ($errors->any())
<div class="m-4 p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<!--上限アラート-->
@if($comments->total() > 990)
{{-- @if($comments->count() > 990) --}}
<div class="m-4 p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-200 dark:text-yellow-800">
    1000レスを超えると書き込みができなくなります</div>
@endif
<!--スレッドタイトル-->
<div id="headerarea" class="px-5 pb-5 pt-3" data-threadid="{{$header->id}}">
    <h1 class="font-bold text-xl text-gray-700 mb-5">{{$header->title}}</h1>
    <div class="flex font-light text-sm text-gray-500">
        <div class="p-1">{{$header->created_at->format('y/m/d H:i')}}</div>
        {{-- category --}}
        @if($category->id > 0)
        &nbsp;<a href="{{ route('category.show', ['categorynm'=>$category->categorynm])}}">
            <div class="bg-gray-200 p-1 text-xs text-gray-500 text-right w-auto rounded">{{$category->categorynm}}</div>
        </a>&nbsp;
        @endif
        {{-- comment --}}
        @if(isset($header->commentcounter->counter))
        <div class="p-1 font-bold text-blue-500"><i
                class="fa-solid fa-comment"></i>&nbsp;{{$header->commentcounter->counter}}</div>
        @endif
    </div>
</div>

{{-- dlsite --}}

{{-- --}}




<div class="text-right mt-4 mb-5 mr-3">
    <a href="#commentform"
        class="border px-7 py-3 bg-blue-600 text-sm text-white font-semibold rounded hover:bg-blue-500">コメントを投稿</a>
</div>
<!--スレッドタイトル-->

<div class="grid grid-cols-2 mb-2">
    <!--urlに応じて変更-->
    <a href="{{ route('thread.show', ['id' =>$header->id, 'sort' => 'new'])}}"
        class="{{strpos(url()->current(),'new') ? 'border-b-4 border-orange-600 bg-gray-200 font-bold py-4 text-center' : 'bg-gray-200 hover:bg-gray-200 py-4 text-center text-gray-500'}}">
        <div class="border-r border-gray-400">新規順</div>
    </a>

    <a href="{{ route('thread.show', ['id' =>$header->id, 'sort' => 'old'])}}"
        class="{{strpos(url()->current(),'old')  ? 'border-b-4 border-orange-600 bg-gray-200 py-4 font-bold text-center' : 'bg-gray-200 hover:bg-gray-200 py-4 text-center text-gray-500'}}">
        <div class="">古い順</div>
    </a>
</div>




@if($comments->hasPages())
<div class="flex justify-center border-b p-3 mb-4 mt-2">{!! $comments->links('pagination::original_pagination_view') !!}
</div>
@endif


<!--メインカラムの大枠-->
<div id="commentbody">


    {{-- 広告 --}}

    {{-- --}}

    <!--記事一覧-->
    @foreach($comments as $comment)
    <div class="border-b px-5 pb-5 pt-3">

        <!--リプ取得用-->
        <span id="{{$comment->comment_serial}}" class="reswaku">
            <!--情報欄-->
            <div class="flex justify-between text-xs text-gray-500 mb-3">
                <div class="meta">
                    <span class="metanumber">{{$comment->comment_serial}}</span>:
                    <span class="<?php echo $comment->kanriflag == " 1" ? "text-red-500" : "text-green-700" ; ?>
                        name">{{$comment->name}} </span>&nbsp;<span
                        class="metadate">{{$comment->created_at->format('y/m/d H:i')}} </span> &#040;<span
                        class="metaid"><a class="hover:underline"
                            href="{{ route('comment.idsearch', ['id'=>$comment->user_id])}}">{{$comment->user_id}}</a></span>&#041;
                </div>
            </div>
            <!--内容-->
            <div data-commentid="{{$comment->id}}"
                class="thcombody font-bold text-md text-gray-700 mb-2 mt-3 rescontent">
                {!! ($comment->body) !!}
            </div>
            {{-- 返信ポップアップウィンドウ　※旧仕様、いづれ削除 --}}
            <div class="rep-window">
                <div class="replyid"></div>
                <button class="js-close button-close">閉じる</button>
            </div>
        </span>
        <!--下情報欄-->
        <div class="pt-4 mb-1 flex flex-row-reverse">
            {{-- 返信フォーム --}}
            @if($header->commentcounter->counter < 1000) <button class="js-repform text-gray-400 text-sm mt-2"
                data-repserial="{{$comment->comment_serial}}" data-repid="{{$comment->id}}"><i
                    class="fa-solid fa-reply mr-1"></i>返信</button>
                @endif
                {{-- LIKEボタン --}}
                
                {{-- @if($category->id != 3) --}}
                <div class="flex text-gray-400 items-center mr-4">
                    <button class="like-toggle mr-2" data-review-id="{{ $comment->id }}"><i
                            class="fa-solid fa-thumbs-up text-sm mr-1"></i><span
                            class="like-counter text-sm mr-2">{{isset($comment->like_count) ? $comment->like_count :
                            '0'}}</span></button>
                    
                    @if($category->id != 3)
                    <button class="dislike-toggle" data-review-id="{{ $comment->id }}"><i
                            class="fa-solid fa-thumbs-down relative top-1 mr-2 text-sm"></i><span
                            class="dislike-counter text-md">{{--{{isset($comment->dislike_count) ?
                            $comment->dislike_count : '0'}}--}}</span></button>
                    @endif
                </div>
                {{-- @endif --}}
                {{-- --}}
        </div>
        {{-- 返信件数JQ --}}
        @if($comment->counter > 0 && $header->commentcounter->counter < 1000) <div class="text-sm">
            <button class="js-replist" data-reptarget="{{$comment->id}}"><i
                    class="fa-solid fa-caret-down"></i>&nbsp;{{$comment->counter}}件の返信を表示</button>
            {{-- 返信ボタンを一度押した後 --}}
            <button class="js-replist-done"><i
                    class="fa-solid fa-caret-down"></i>&nbsp;{{$comment->counter}}件の返信を表示</button>
            <button class="js-replist-close"><i class="fa-solid fa-caret-up"></i>&nbsp;閉じる</button>
    </div>
    {{--返信リスト入れ物 --}}
    <div class="reparea">
    </div>
    @endif
</div>


{{-- --}}

@endforeach
<!--記事一覧-->


</div>


{{-- mtad --}}

{{-- --}}


<!--メインカラムの大枠-->
@if($comments->hasPages())
<div class="flex justify-center  p-3 mb-4 mt-2">{!! $comments->links('pagination::original_pagination_view') !!}</div>
@endif
{{-- 上限 --}}
@php
// ($header->commentcounter->counter < 1000) dump($header->commentcounter->counter);
// @if($comments->max('comment_serial') < 1000)
@endphp
    @if($header->commentcounter->counter < 1000) @include("parts.comment_form") @endif {{-- 実験、フォームjquery用 --}} 
    <div class="com-form border-b border-t py-3">
        {{-- フォームキャンセル --}}
        <button class="close-rep text-lg text-gray-500 mb-1 float-right"><i class="fa-solid fa-xmark "></i></button>
        {{-- <form method="post" class="w-full" action="{{url('/rep')}}"> --}}
            <form method="GET" class="w-full" action="{{url('/confirmrep')}}">
                @csrf
                <div class="mb-3">
                    <input type="text" name="name" value="名無し" placeholder="名前を入力"
                        class="w-2/5 p-2 border placeholder-gray-500 placeholder-opacity-50" />
                </div>
                <div class="mb-3">
                    <textarea name="body" placeholder="内容を記入してください"
                        class="w-full h-40 p-2 border placeholder-gray-500 placeholder-opacity-50">
</textarea>
                </div>
                <input type="hidden" class="rep_thread_id" name="thread_id" value="{{$header->id}}" />
                <input type="hidden" class="rep_rep_id" name="rep_id" value="" />
                <input type="submit"
                    class="border px-10 py-2 bg-white text-base text-gray-700 font-semibold rounded hover:bg-gray-100"
                    value="返信する" />
            </form>
            </div>
            {{-- フォームjquery用 --}}

            {{-- dlsite --}}
            

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
                    <a href="{{ route('thread.show', ['id'=>$newcomment->thread->id])}}">
                        <div>{{$newcomment->thread->title}}</div>
                    </a>
                    {{-- <div class="h-10 truncate whitespace-nowrap overflow-y-hidden">{!! ($newcomment->body) !!}
                    </div> --}}
                    <div class="h-10 truncate whitespace-nowrap overflow-y-hidden">
                        <?php echo strip_tags($newcomment->body);  ?>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            {{-- カテゴリ --}}
            @if(isset($ctlist))
            <div class="w-auto h-auto bg-white">
                <h2 class="p-2 text-base text-black font-bold text-center">カテゴリ</h2>
                <div class="flex flex-wrap p-2">
                    @foreach($ctlist as $one)
                    <a href="{{ route('category.show', ['categorynm'=>$one->categorynm])}}">
                        <div class="bg-gray-200 p-1 text-sm text-gray-500 w-auto m-1 rounded">{{$one->categorynm}}</div>
                    </a>
                    @endforeach
                </div>

            </div>
            @endif

            {{-- 個別サイド --}}
            {{-- <div class="w-auto h-auto bg-white">
                <h2 class="p-4 m-2 text-base text-black font-bold text-center">スポンサー</h2>

                
            </div> --}}

            {{-- ページトップボタン --}}
            <a href="#commentform">
                <div class="pagebutton">
                    {{-- <i class="fa-solid fa-chevron-down"></i> --}}
                    <i class="fas fa-angle-down"></i>
                </div>
            </a>
            {{-- オーバーレイ --}}
            <div id="overlay" class="overlay"></div>
            @endsection
            {{-- jquery --}}
            @section('otherfile')
            <script src="{{ asset('/js/like.js?20220712') }}"></script>
            <script src="{{ asset('/js/reply.js?202207123') }}"></script>
            <script src="{{asset('/js/rep.js?20220607')}}"></script>
            <script src="{{ asset('/js/modalcheck.js?20220706') }}"></script>
            <script src="{{ asset('/js/tweetReplace.js') }}"></script>
            @endsection