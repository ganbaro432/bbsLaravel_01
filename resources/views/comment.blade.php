@extends('layouts.app')
@section('sectionTitle')
{{$header->thread->title}}
@endsection
@section('sectionMetatag')
{{-- likebutton --}}
<script src="{{ mix('js/like.js') }}"></script>
@endsection

@section('content')

<!--メインカラム-->

    <!--アラート-->
    @if(session('message'))
    <div class="m-4 p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800">{{session('message')}}</div>
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
    <!--元スレ-->
 
    <div class="px-5 pb-5 pt-3 border-b-2">
        <a href="{{ route('thread.show', ['id'=>$header->thread_id])}}"><div class="w-24 text-center border px-6 py-2 mb-6 bg-white text-sm text-gray-700 font-semibold rounded hover:bg-gray-100">戻る</div></a>
        <h1 class="font-bold text-xl text-gray-700 mb-3">{{$header->thread->title}}<h2>
        <p class="font-light text-sm text-gray-500">
          {{$header->thread->created_at->format('y/m/d H:i')}}
        </p>
    </div>
    <!--元スレ-->

    <!--返信元コメント-->

    <div class="border-b-2 px-5 pb-5 pt-3">
        <!--情報欄-->
        <div class="flex font-light text-xs text-gray-500 mb-3">
            {{$header->comment_serial}}:
            <span class="<?php echo $header->kanriflag == "1" ? "text-red-500" : "text-green-700"; ?>">{{$header->name}}</span>&nbsp;{{$header->created_at->format('y/m/d H:i')}}&nbsp;&#040;<a class="hover:underline" href="{{ route('comment.idsearch', ['id'=>$header->user_id])}}">{{$header->user_id}}</a>&#041;
        </div>
         <!--情報欄-->
         <div class="thcombody font-bold text-xl text-gray-700 mb-2 mt-3">{!! $header->body !!}</div>
         <div class="py-4 mb-1">
            {{-- LIKEボタン --}}
           <div class="float-right">
               <div class="flex text-gray-400 items-center">
                   <button class="like-toggle mr-2" data-review-id="{{ $header->id }}"><i class="fa-solid fa-thumbs-up text-md mr-1"></i><span class="like-counter text-md mr-1">{{isset($header->like_count) ? $header->like_count : '0'}}</span></button>
                   <button class="dislike-toggle" data-review-id="{{ $header->id }}"><i class="fa-solid fa-thumbs-down relative top-1 mr-1 text-md"></i><span class="dislike-counter text-md">{{isset($header->dislike_count) ? $header->dislike_count : '0'}}</span></button> 
               </div>
           </div>
           {{--  --}}
        </div>          
    </div>
    <!--返信元-->
    @if($allcom->count() < 1000)
    @include("parts.rep_form")
    @endif
    
    
    <!--メインカラムの大枠-->
    <div id="commentbody">

        <!--記事一覧-->
        @foreach($comments as $comment)
        <div class="border-b-2 px-5 pb-5 pt-3">
            
            
            
            <!--情報欄-->
            <div class="flex justify-between text-xs text-gray-500 mb-3">
                <div class="">
                    {{$comment->comment_serial}}.
                    <span class="<?php echo $comment->kanriflag == "1" ? "text-red-500" : "text-green-700"; ?>">{{$comment->name}} </span>&nbsp;{{$comment->created_at}} ID:&#040;<a class="hover:underline" href="{{ route('comment.idsearch', ['id'=>$comment->user_id])}}">{{$comment->user_id}}</a>&#041;
                </div>
                <div class="">
                    <a href="{{ route('comment.show', ['tid'=>$comment->thread_id, 'cid'=>$comment->id])}}" class="font-light text-xs text-gray-400"><i class="fa-solid fa-reply"></i>&nbsp;返信</a>
                </div>
            </div>            
             <!--情報欄-->
             <div class="thcombody font-semibold text-md text-gray-700 mb-2 mt-3">{!! $comment->body !!}</div>
             <div class="py-4 mb-1">
                {{-- LIKEボタン --}}
               <div class="float-right">
                   <div class="flex text-gray-400 items-center">
                       <button class="like-toggle mr-2" data-review-id="{{ $comment->id }}"><i class="fa-solid fa-thumbs-up text-md mr-1" data-review-id="{{ $comment->id }}"></i><span class="like-counter text-gray-500 text-md mr-1">{{isset($comment->like_count) ? $comment->like_count : '0'}}</span></button>
                       <button class="dislike-toggle" data-review-id="{{ $comment->id }}"><i class="fa-solid fa-thumbs-down relative top-1 mr-1 text-md"></i><span class="dislike-counter text-gray-500 text-md">{{isset($comment->dislike_count) ? $comment->dislike_count : '0'}}</span></button> 
                   </div>
               </div>
               {{--  --}}
            </div>               


        </div>
        @endforeach
        <!--記事一覧-->

        {{$comments->links()}}
    </div>
    <!--メインカラムの大枠-->




<!--メインカラム-->

@endsection