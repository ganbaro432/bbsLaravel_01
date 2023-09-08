@extends('member.m_app')
@section('content')

<!--メインカラム-->    
    <!--メインカラムの大枠-->
    <div class="">
        <div class="bg-red-600 p-4 font-bold text-center text-white">
            <h1>削除専用ページ</h1>
        </div>
        <!--記事一覧-->
        @if(isset($keyword))<h2 class="p-4 m-2 text-base text-black font-bold text-center">検索ワード：{{$keyword}}</h2>@endif

        <a href="{{ url('/member/index')}}"><div class="w-24 text-center border px-6 py-2 mb-6 bg-white text-sm text-gray-700 font-semibold rounded hover:bg-gray-100">一覧に戻る</div></a>

        @foreach($posts as $thread)
        <a href="{{ route('member.show', ['id'=>$thread->id])}}">
        <div class="border-b-2 p-5 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50">
            <div class="font-bold text-xl text-gray-700 mb-2">
            {{$thread->title}}
            </div>
            
            <!--情報欄-->
            <div class="flex font-light text-sm text-gray-500">
             <div>{{$thread->created_at}}</div>
             @if(isset($thread->commentcounter->counter))
             &nbsp;&nbsp;<div class="">コメント数:{{$thread->commentcounter->counter}}</div>
             @endif
             @if(isset($counter))
             &nbsp;&nbsp;コメント数:{{$counter->where('thread_id', $thread->id)->first()->counter}}
             @endif
            </div>
             <!--情報欄-->

             @if(count($posts) < 1)
            <p>投稿がありません</p>
            @endif
        </div>
        </a>



        @endforeach
        <!--記事一覧-->

        
    </div>
    <!--メインカラムの大枠-->
    {{ $posts->links() }}

<!--メインカラム-->
@endsection
