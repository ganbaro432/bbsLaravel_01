@extends('layouts.app')
@section('content')

<!--メインカラム-->

    
    
    <!--メインカラムの大枠-->
    <div class="">
        <!--記事一覧-->
        @if(isset($threads))<h2 class="p-4 m-2 text-base text-black font-bold text-center">カテゴリ：{{$categorynm}}</h2>@endif
        @foreach($threads as $thread)
        <a href="{{ route('thread.show', ['id'=>$thread->id])}}">
        <div class="border-b-2 p-5 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50">
            <div class="font-bold text-xl text-gray-700 mb-2">
            {{$thread->title}}
            </div>
            
            <!--情報欄-->
            <div class="flex font-light text-sm text-gray-500">
             <div>{{$thread->created_at}}</div>
             @if(isset($thread->counter))
             &nbsp;&nbsp;<div class="">コメント数:{{$thread->counter}}</div>
             @endif
            </div>
             <!--情報欄-->

             @if(count($threads) < 1)
            <p>投稿がありません</p>
            @endif
        </div>
        </a>



        @endforeach
        <!--記事一覧-->

        
    </div>
    <!--メインカラムの大枠-->
    {{ $threads->links() }}

<!--メインカラム-->
@endsection

<!--メインだけのサイド-->
@section('sides')

{{-- 検索機能 --}}
<div class="flex items-center justify-center ">
    <form action="{{ route('search.show') }}" method="GET">
    <div class="flex border-2 border-gray-200 rounded">       
        <input type="text" class="px-4 py-2 w-auto" name="keyword" placeholder="検索">
        <button type="submit" class="px-4 text-white bg-gray-600 border-l "><i class="fa-solid fa-magnifying-glass"></i></button>   
    </div>
</form>
</div>

<!--ボタン-->
<div class="mb-2 p-1">
    <button onclick="location.href='{{ url('/thread/post')}}'" class="border w-full px-2 py-2 bg-blue-500 text-lg text-white font-semibold rounded hover:bg-blue-400">スレッド作成</button>
</div>

{{-- サイド最新コメント --}}
<div class="w-auto h-auto bg-white">
    <h2 class="p-4 m-2 text-base text-black font-bold text-center">最新コメント</h2>
    @if(isset($newcomments))
    @foreach($newcomments as $newcomment)
    
    <div class="newcomment mb-2 p-2 border-b-2 font-light text-sm">
        <a href="{{ route('thread.show', ['id'=>$newcomment->thread->id])}}"><div>{{$newcomment->thread->title}}</div></a>
        {{-- <div class="h-10 truncate whitespace-nowrap overflow-y-hidden">{!! ($newcomment->body) !!}</div> --}}
        <div class="h-10 truncate whitespace-nowrap overflow-y-hidden"><?php echo strip_tags($newcomment->body);  ?></div>
    </div>
    @endforeach
    @endif
</div>
 {{-- カテゴリ --}}
 @if(isset($category))
 <div class="w-auto h-auto bg-white">
     <h2 class="p-2 text-base text-black font-bold text-center">カテゴリ</h2>
     <div class="flex flex-wrap p-2">
         @foreach($category as $one)
         <a href="{{ route('category.show', ['categorynm'=>$one->categorynm])}}"><div class="bg-gray-200 p-1 text-sm text-gray-500 w-auto m-1 rounded">{{$one->categorynm}}</div></a>
         @endforeach
     </div>
 </div>
 @endif

@endsection