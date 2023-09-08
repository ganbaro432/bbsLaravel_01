@extends('member.m_app')

@section('content')


<!--メインカラム-->

    <a href="{{url('/member/index')}}">
    <div class="bg-red-600 hover:bg-red-700 p-4 font-bold text-center text-white">
        <h1>削除専用ページ/一覧へ</h1>
    </div>
    </a>
    <!--スレッドタイトル-->
    <div class="px-5 pb-5 pt-3">
        <h1 class="font-bold text-xl text-gray-700 mb-3">{{$header->title}}<h2>
        <p class="font-light text-sm text-gray-500">
            {{$header->created_at}}/ID:{{$header->id}}
        </p>
    </div>
    <!--スレッドタイトル-->


    <!--ソートボタン-->
    <div class="flex">
        <a href="{{ route('member.show', ['id' =>$header->id, 'sort' => 'new'])}}" class="w-1/2 "><div class="{{strpos(url()->current(),'new') ? 'border bg-gray-900 p-4 m-2  font-bold text-center text-white' : 'border bg-gray-100 hover:bg-gray-200 p-4 m-2  font-bold text-center'}}">新規順</div></a>
        <a href="{{ route('member.show', ['id' =>$header->id, 'sort' => 'old'])}}" class="w-1/2 "><div class="{{strpos(url()->current(),'old') ? 'border bg-gray-900 p-4 m-2  font-bold text-center text-white' : 'border bg-gray-100 hover:bg-gray-200 p-4 m-2  font-bold text-center'}}">古い順</div></a>

        
    </div>
    
    
    <!--メインカラムの大枠-->
    <div class="resmatome">
        
        <!--記事一覧-->
        @foreach($comments as $comment)
        <div class="border-b-2 px-5 pb-5 pt-3 reswaku">
             
            <!--情報欄-->
            <div class="flex font-light text-xs text-gray-500 mb-3 resinfo">
             {{$comment->comment_serial}}.
             名前:{{$comment->name}} {{$comment->created_at}}/{{$comment->user_id}}
             
            </div>
             <!--内容-->
             <div class="font-bold text-md text-gray-700 mb-2 mt-3 rescontent">{!! $comment->body !!}</div>
             <!--下情報欄-->
             <div class="py-4 mb-1">
                 {{-- 管理人専用リプライ --}}
                 @if(session('simple_auth') == "kanrinin2" || session('simple_auth') == "asoxksla01")
                 <a href="{{ route('mcomment.show', ['id'=>$comment->id])}}" class="font-light text-xs text-gray-500">返信する</a>
                 @endif                 
                <div class="float-right">
                    <!--削除ボタン-->
                    <form method="post" class="" action="{{url('/updel')}}">
                        @csrf 
                        <input type="hidden" name="id" value="{{$comment->id}}" />
                        <input type="hidden" name="thread_id" value="{{$comment->thread_id}}" />
                        <input type="hidden" name="comment_body" value="{{$comment->body}}" />
                        <input type="hidden" name="kanriflag" value="2"  />
                    <input type="submit" class="bg-red-600 hover:bg-red-500 text-white rounded px-4 py-2 font-bold cursor-pointer" value="削除する" />
                    </form>
                   </div>
             </div>
             
        </div>
        @endforeach
        <!--記事一覧-->
    </div>
    <!--メインカラムの大枠-->



<!--メインカラム-->
@endsection
