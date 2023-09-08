@extends('layouts.app')
@section('content')

<!--メインカラム-->

    
    
    <!--メインカラムの大枠-->
    <div class="">
        <!--記事一覧-->
        @if(isset($id))<h2 class="p-4 m-2 text-base text-black font-bold text-center">検索ID:{{$id}}</h2>@endif
        @foreach($threads as $thread)
        <a href="{{ route('thread.show', ['id'=>$thread->thid])}}">
        <div class="border-b-2 p-5 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50">
            <div class="thcombody font-bold text-xl text-gray-700 mb-2">
            <?php echo strip_tags($thread->body);  ?>
            </div>    
            <!--情報欄-->
            <div class="flex font-light text-sm text-gray-500">
                {{$thread->created_at->format('m/d H:i')}}&nbsp;
                スレッド： {{$thread->thtitle}}
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
<div class="mb-2 p-1">
    <form action="{{ route('search.show') }}" method="GET">
        <input class="h-8" type="text" name="keyword" value="">
        <input class="border h-8 px-2 bg-white text-gray-700 rounded hover:bg-gray-100" type="submit" value="検索">
      </form>
</div>

<!--ボタン-->
<div class="mb-2 p-1">
    <button onclick="location.href='{{ url('/thread/post')}}'" class="border w-full px-2 py-2 bg-blue-500 text-lg text-white font-semibold rounded hover:bg-blue-400">スレッド作成</button>
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

{{-- サイド最新コメント --}}
<div class="w-auto h-auto bg-white">
    <h2 class="p-4 m-2 text-base text-black font-bold text-center">最新コメント</h2>
    @if(isset($newcomments))
    @foreach($newcomments as $newcomment)
    
    <div class="newcomment mb-2 p-2 border-b-2 font-light text-sm">
        <a href="{{ route('thread.show', ['id'=>$newcomment->thread->id])}}"><div>{{$newcomment->thread->title}}</div></a>
        <div class="h-10 truncate whitespace-nowrap overflow-y-hidden"><?php echo strip_tags($newcomment->body);  ?></div>
    </div>
    @endforeach
    @endif
</div>

@endsection