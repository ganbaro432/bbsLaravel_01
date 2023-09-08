@extends('member.m_app')

@section('content')


<!--メインカラム-->

    <a href="{{url('/member/index')}}">
    <div class="bg-red-600 hover:bg-red-700 p-4 font-bold text-center text-white">
        <h1>削除専用ページ/一覧へ</h1>
    </div>
    </a>
    
    <!--メインカラムの大枠-->
    <div class="">
        @if(session('simple_auth') == "kanrinin2")
        {{-- 登録作業 --}}
        <div class="my-10">
            <h2 class="">ボランティア登録</h2>
            <form method="post" class="w-full p-2" action="{{url('/memberregist')}}">
                @csrf
                {{-- user_id --}}
                <div class="mb-3">
                    <input type="text" name="userid" value=""  pattern="^[0-9a-zA-Z]+$" placeholder="user_id" class="py-2 border placeholder-gray-500 placeholder-opacity-50" />
                </div>
                {{-- user_name --}}
                <div class="mb-3">
                    <input type="text" name="username" placeholder="user_name" class="py-2 border placeholder-gray-500 placeholder-opacity-50" />
                </div>
                {{-- pass --}}
                <div class="mb-6">
                    <input type="password" name="pass" value="" placeholder="pass" class="py-2 border placeholder-gray-500 placeholder-opacity-50" />
                </div>
                {{-- 認証 --}}
                <div class="mb-6">
                    <input type="text" name="check" value="" pattern="^[0-9a-zA-Z]+$" placeholder="あなたの認証番号" class="py-2 border placeholder-gray-500 placeholder-opacity-50" />
                </div>                
                <input type="submit" class="border px-10 py-2 bg-blue-600 text-base text-white font-semibold rounded hover:bg-blue-500" value="登録" />
            </form>            
        </div>
        


        {{-- リスト --}}
        <div class="grid grid-cols-12 bg-black text-white text-center">
            {{-- id --}}
            <div class="col-span-1 border-r">id</div>
            {{-- user_id --}}
            <div class="col-span-5 border-r">user</div>
            {{-- user_name --}}
            <div class="col-span-5 border-r">name</div>
            {{-- 編集ボタン --}}
            <div class="col-span-1">ボタン</div>
        </div>
        @foreach($members as $person)
        {{-- 一覧 --}}
        <div class="grid grid-cols-12 py-3 text-center">
            {{-- id --}}
            <div class="col-span-1 py-1 border-r">{{$person->id}}</div>
            {{-- user_id --}}
            <div class="col-span-5 py-1 border-r">{{$person->user_id}}</div>
            {{-- user_name --}}
            <div class="col-span-5 py-1 border-r">{{$person->user_name}}</div>
            {{-- 削除ボタン --}}
            <div class="col-span-1">
                <form method="post" class="text-center" action="{{url('memberdel')}}">
                    @csrf 
                    <input type="hidden" name="id" value="{{$person->id}}" />
                <input type="submit" class="text-black font-bold hover:bg-red-300" value="削除" />
                </form>    
            </div>
        </div>
        @endforeach

        @endif
    </div>
    <!--メインカラムの大枠-->



<!--メインカラム-->
@endsection
