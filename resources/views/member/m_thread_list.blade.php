{{-- @extends('layouts.app') --}}
@extends('member.m_app')

@section('content')

<!--メインカラム-->

    
    <div class="bg-red-600 p-4 font-bold text-center text-white">
        <h1>削除専用ページ</h1>
    </div>

    <!--ログアウト機能-->
    <div class="border w-auto m-5 px-1 py-2 bg-white rounded hover:bg-gray-100 text-center">
        <form method="post" action="{{ url('vlogout') }}">
            @csrf 
        <input type="submit" class="text-base text-gray-700 font-semibold" value="ログアウト" />
        </form>
    </div>

    {{-- 管理人専用削除予定表示 --}}
    <div class="px-5 pb-5 pt-3">
        @if(session('simple_auth') == "kanrinin2")
        <h2 class="font-bold text-xl text-pink-600 text-center py-2 mb-8">削除予定</h2>
        <h2 class="font-bold border-b-2 py-2 mb-1">スレ完走</h2>
        @foreach ($fullthreads as $thread)
        <a href="{{ route('thread.show', ['id'=>$thread->id])}}">
        <div class="border-b p-2 mb-3 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50">
            <div class="font-bold text-xl mb-5" style="color:#39a9bf">
            {{$thread->title}}
            </div>
            <!--情報欄-->
            <div class="flex font-light text-sm text-gray-400">
            <div class="p-1">ID:{{$thread->id}}レス数:{{$thread->counter}} </div>     
            </div>
        </div>
        </a>
        @endforeach
        @if($fullthreads->isEmpty())
        <p>投稿がありません</p>
        @endif

        <h2 class="font-bold border-b-2 py-2 mb-1 mt-10">準備3週間前、20レス以下</h2>
        @foreach ($poorthreads as $thread)
        <a href="{{ route('thread.show', ['id'=>$thread->id])}}">
        <div class="border-b p-2 mb-3 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50">
            <div class="font-bold text-xl mb-5" style="color:#39a9bf">
            {{$thread->title}}
            </div>
            <!--情報欄-->
            <div class="flex font-light text-sm text-gray-400">
            <div class="p-1">ID:{{$thread->id}}レス数:{{$thread->counter}} 最終投稿日:{{$thread->upd}}</div>     
            </div>
            @if(count($poorthreads) < 1)
            <p>投稿がありません</p>
            @endif
        </div>
        </a>
        @endforeach

        <h2 class="font-bold border-b-2 py-2 mb-1 mt-10">4週間前、20レス以下</h2>
        @foreach ($oldthreads as $thread)
        <a href="{{ route('thread.show', ['id'=>$thread->id])}}">
        <div class="border-b p-2 mb-3 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50">
            <div class="font-bold text-xl mb-5" style="color:#39a9bf">
            {{$thread->title}}
            </div>
            <!--情報欄-->
            <div class="flex font-light text-sm text-gray-400">
            <div class="p-1">ID:{{$thread->id}}レス数:{{$thread->counter}} 最終投稿日:{{$thread->upd}}</div>     
            </div>
            @if(count($poorthreads) < 1)
            <p>投稿がありません</p>
            @endif
        </div>
        </a>
        @endforeach  
        @endif  
    </div>
    {{-- 管理人専用削除予定表示 --}}


    <!--ソートボタン-->
    <div class="flex border-b-2 border-dashed border-zinc-200">
        
        <!--urlに応じて変更-->
        <a href="{{url('/member/index', ['sort' => 'new'])}}" class="w-1/2 "><div class="{{strpos(url()->current(),'new') ? 'border bg-gray-900 p-4 m-2  font-bold text-center text-white' : 'border bg-gray-100 hover:bg-gray-200 p-4 m-2  font-bold text-center'}}">新規順</div></a>
    
        <a href="{{url('/member/index', ['sort' => 'update'])}}" class="w-1/2 "><div class="{{strpos(url()->current(),'update')  ? 'border bg-gray-900 p-4 m-2  font-bold text-center text-white' : 'border bg-gray-100 hover:bg-gray-200 p-4 m-2  font-bold text-center'}}">更新</div></a>
    </div>
    
    
    <!--メインカラムの大枠-->
    <div class="">
        <!--記事一覧-->
        @foreach($threads as $thread)
        
        <div class="border-b-2 ">
            <div class="grid grid-cols-6">
                
                    <div class="p-2 col-span-5 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50 border-r border-gray-100">
                        <a href="{{ route('member.show', ['id'=>$thread->id])}}">
                        <div class="font-bold text-lg text-gray-700 mb-2">
                        {{$thread->title}}
                        </div>            
                        <!--情報欄-->
                        <div class="flex font-light text-sm text-gray-500">
                        <div>{{$thread->created_at}}</div>
                        &nbsp;&nbsp;<div class="">コメント数:{{$thread->commentcounter->counter}}</div>
                        </div>
                        <!--情報欄-->
                        </a>
                    </div>
                {{-- 削除用 --}}
                <div class="col-span-1 flex justify-center items-center">
                    @if(session('simple_auth') == "kanrinin2")
                    {{-- @if(session('simple_auth') == "asoxksla01") --}}
                    <a href="{{route('member.thdel', ['id'=>$thread->id])}}">
                   <p class=" text-white text-center bg-red-600 p-2 rounded-md hover:bg-black">削除</p>
                    </a>
                    @endif
                </div>
            </div>

        </div>

        @endforeach
        <!--記事一覧-->

        
    </div>
    <!--メインカラムの大枠-->

<!--メインカラム-->
@endsection
