@extends('layouts.app')
@section('content')


<!--メインカラム-->    
    <!--スレッドタイトル-->
    <div class="px-5 pb-5 pt-3">
        <h1 class="font-bold text-xl text-gray-700 mb-3">{{$header->title}}<h2>
        <p class="font-light text-sm text-gray-500">
            {{$header->created_at}}/ID:{{$header->id}}
            @if(isset($header->commentcounter->counter))
            コメント数{{$header->commentcounter->counter}}
             @endif
        </p>
        <div class="text-right">
            <a href="">コメントを投稿</a>
        </div>
    </div>
    <!--スレッドタイトル-->
    

    <!--ソートボタン-->
    <div class="flex">
        <div class="{{strpos(url()->current(),'new') ? 'w-1/2 border bg-gray-900 p-4 m-2  font-bold text-center text-white' : 'w-1/2 border bg-gray-100 hover:bg-gray-200 p-4 m-2  font-bold text-center'}}"><a href="{{ route('thread.show', ['id' =>$header->id, 'sort' => 'new'])}}">新規順</a></div>
        <div class="{{strpos(url()->current(),'old') ? 'w-1/2 border bg-gray-900 p-4 m-2  font-bold text-center text-white' : 'w-1/2 border bg-gray-100 hover:bg-gray-200 p-4 m-2  font-bold text-center'}}"><a href="{{ route('thread.show', ['id' =>$header->id, 'sort' => 'old'])}}">古い順</a></div>
    </div>
    
    
    <!--メインカラムの大枠-->
    <div class="">

        <!--記事一覧-->
        @foreach($comments as $comment)
        <div class="border-b-2 px-5 pb-5 pt-3">
             
            <!--情報欄-->
            <div class="flex font-light text-xs text-gray-500 mb-3">
             {{$comment->comment_serial}}.
             名前:{{$comment->name}} {{$comment->created_at}}
             
             
            </div>
             <!--情報欄-->

             <div class="font-bold text-xl text-gray-700">{{$comment->body}}</div>
             <div class="text-right">
                 <a href="{{ route('comment.show', ['id'=>$comment->id])}}" class="font-light text-xs text-gray-500">返信する</a>
                </div>

        </div>
        @endforeach
        <!--記事一覧-->

        
    </div>
    <!--メインカラムの大枠-->
    @include("parts.comment_form")


{{--カウンター表示--}}
@if(isset($counter->counter))
カウンター:{{$counter->counter}}<br>
@endif

<!--メインカラム-->

@endsection
