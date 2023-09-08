@extends('member.m_app')

@section('content')


<!--メインカラム-->

    <a href="{{url('/member/index')}}">
    <div class="bg-red-600 hover:bg-red-700 p-4 font-bold text-center text-white">
        <h1>削除専用ページ/一覧へ</h1>
    </div>

    <!--ログアウト機能-->
    <div class="border w-auto m-5 px-1 py-2 bg-white rounded hover:bg-gray-100 text-center">
        <form method="post" action="{{ url('vlogout') }}">
            @csrf 
        <input type="submit" class="text-base text-gray-700 font-semibold" value="ログアウト" />
        </form>
    </div>

    </a>
    <!--スレッドタイトル-->
    <div class="px-5 pb-5 pt-3 text-center mt-5">
        <h1 class="font-bold text-2xl text-gray-700 mb-3">スレッド名:{{$header->title}}<h2>
        <p class="font-light text-md text-gray-600">
            {{$header->created_at}}/コメント数{{$header->commentcounter->counter}}
        </p>
    </div>
    {{-- 削除ボタン --}}
    <form method="post" class="text-center mt-10" action="{{url('/thdel')}}">
        @csrf 
        <input type="hidden" name="id" value="{{$header->id}}" />
    <input type="submit" class="bg-orange-600 text-white font-bold p-4 hover:bg-black" value="削除する" />
    </form>    
    {{-- <div class="text-center mt-10">
        <a href="{{ route('thdel', ['id'=>$header->id])}}" class="bg-orange-600 text-white font-bold p-4 hover:bg-black">スレッドを削除する</a>
    </div> --}}
    <!--メインカラムの大枠-->



<!--メインカラム-->
@endsection
