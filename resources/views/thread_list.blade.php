@extends('layouts.app')
@section('content')
@section('sectionMetatag')
{{-- 独自CSS --}}
<style>
    #imgarea img{
       width: 150px;
       height: 80px;
       object-fit: cover; 
    }
   </style>
@endsection

{{-- 記事リスト --}}
<div class="mb-3 px-3">
    <p class="py-3 text-center font-bold border-b"><i class="fa-solid fa-book-open mr-2"></i>投稿記事募集中</p>
</div>
@foreach($articles as $article)
        <a href="{{ route('article.show', ['id'=>$article->id])}}">
        @if(isset($article->path))
        <div class="border-b p-2 mb-3 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50">
            <div class="ml-1">
                <div class="font-bold md:text-lg text-base mb-5 truncate" style="color:#39a9bf">
                    {{$article->title}}
                </div>  
                <div class="flex justify-between font-light text-xs text-gray-400">
                    <div>
                        @if(isset($article->categorynm)) 
                        <span class="bg-sky-100 text-xs text-gray-500 rounded px-2 border-solid border border-sky-300">{{$article->categorynm}}</span>
                        @endif
                    </div>
                    <div>
                        {{$article->created_at->format('y/m/d H:i')}}  
                    </div>  
                </div>
            </div>
        </div>

        @else
        <div class="border-b p-2 mb-3 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50">
            <div class="">
                <div class="font-bold md:text-lg text-base mb-5 truncate" style="color:#39a9bf">
                    {{$article->title}}
                </div>  
                <div class="flex justify-between font-light text-xs text-gray-400">
                    <div>
                        @if(isset($article->categorynm)) 
                        <span class="bg-sky-100 text-xs text-gray-500 rounded px-2 border-solid border border-sky-300">{{$article->categorynm}}</span>
                        @endif
                    </div>
                    <div>
                        {{$article->created_at->format('y/m/d H:i')}}  
                    </div>  
                </div>
            </div>
        </div>
        @endif
        </a>
        @if(count($articles) < 1)
        <p>投稿がありません</p>
        @endif
@endforeach
<p class="my-5 text-center"><a class="px-3 py-2 bg-orange-500 text-sm text-white font-semibold rounded hover:bg-orange-600" href="{{ url('/article/index')}}">記事一覧へ<i class="fa-solid fa-arrow-right"></i></a></p>   
<!--記事一覧-->    

{{-- googlead --}}


    <div class="grid grid-cols-3 mb-2">
        <!--urlに応じて変更-->
        <a href="{{url('/sort', ['sort' => 'new'])}}" class="{{strpos(url()->current(),'new') ? 'border-b-4 border-blue-400 bg-gray-200 font-bold py-4 text-center' : 'bg-gray-200 hover:bg-gray-200 py-4 text-center text-gray-500'}}"><div class="border-r border-gray-400">新規順</div></a>
    
        <a href="{{url('/sort', ['sort' => 'popular'])}}" class="{{strpos(url()->current(),'pop')  ? 'border-b-4 border-blue-400 bg-gray-200 py-4 font-bold text-center' : 'bg-gray-200 hover:bg-gray-200 py-4 text-center text-gray-500'}}"><div class="border-r border-gray-400">人気順</div></a>

        <a href="{{url('/sort', ['sort' => 'update'])}}" class="{{strpos(url()->current(),'update')  ? 'border-b-4 border-blue-400 bg-gray-200 py-4 font-bold text-center' : 'bg-gray-200 hover:bg-gray-200 py-4 text-center text-gray-500'}}"><div class="">更新順</div></a>
    </div>   
 

    
    <!--メインカラムの大枠-->
    <div class="p-3">
        <!--記事一覧-->
        @foreach($threads as $thread)
        <a href="{{ route('thread.show', ['id'=>$thread->id])}}">
        <div class="grid grid-cols-7 border-b py-2 pr-2 mb-3 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50">
            {{-- 左 --}}
            <div class="col-span-1">
                {{-- コメント数 --}}
                @if(isset($thread->commentcounter->counter))
                <div class="pr-4 md:pr-3 text-center ">
                    <p class="text-sm text-gray-400"><i class="fa-solid fa-comment"></i></p>
                    <p class="text-base text-gray-400">{{$thread->commentcounter->counter}}</p>
                    <p class="text-xs text-gray-400">レス</p>
                </div> 
                @elseif(isset($counter))
                <div class="font-light text-sm text-gray-400 p-1"><i class="fa-solid fa-comment"></i>&nbsp;{{$counter->where('thread_id', $thread->id)->first()->counter}}</div>
                @endif
            </div>  
            {{-- 右 --}}
            <div class="col-span-6"> 
                {{-- title --}}
                <div class="font-bold text-base md:text-lg mb-2" style="color:#39a9bf">
                    {{$thread->title}}
                </div>
                <!--情報欄-->
                <div class="flex justify-between font-light text-xs">                    
                    {{-- カテゴリ --}}
                    <div class=" w-auto p-1 ">
                    @if(isset($thread->categorynm))
                    <span class="bg-sky-100 text-xs text-gray-500 rounded px-1 border-solid border border-sky-300">{{$thread->categorynm}}</span>
                    @endif 
                    </div>  
                    {{-- 時刻 --}}
                    <div class="p-1 text-gray-400"> 
                        {{$thread->comments()->latest('created_at')->first('created_at')->created_at->diffForHumans()}}
                    </div>
                </div>                
            </div>                    
        </div>
        </a>

        @if(count($threads) < 1)
        <p>投稿がありません</p>
        @endif
        @endforeach
        <!--記事一覧-->
        

        
    </div>
    <!--メインカラムの大枠-->
    @if($threads->hasPages())
    <div class="flex justify-center border bg-gray-100 p-3 mb-4 mt-2">{!! $threads->links('pagination::original_pagination_view') !!}</div>
    @endif

<!--メインカラム-->
@endsection

<!--メインだけのサイド-->
@section('sides')


<!--スレッド作成ボタン-->
<div class="mb-2 md:p-0 p-4">
    <button onclick="location.href='{{ url('/thread/post')}}'" class="border w-full px-2 py-2 bg-blue-500 text-lg text-white font-semibold rounded hover:bg-blue-300"><i class="fa-solid fa-pen-to-square mr-2"></i>スレッド作成</button>
</div>

{{-- 検索機能 --}}
<div class="flex items-center justify-center ">
    <form action="{{ route('search.show') }}" method="GET">
    <div class="flex border-2 border-gray-200 rounded">       
        <input type="text" class="px-3 py-2 w-11/12" name="keyword" placeholder="検索">
        <button type="submit" class="px-2 text-white bg-gray-500 border-l "><i class="fa-solid fa-magnifying-glass"></i></button>   
    </div>
</form>
</div>


{{-- サイド最新コメント --}}
<div class="w-auto h-auto bg-white">
    <h2 class="p-4 m-2 text-base text-black font-bold text-center">最新コメント</h2>
    @if(isset($newcomments))
    @foreach($newcomments as $newcomment)
    
    <div class="newcomment mb-2 p-2 border-b font-light text-sm">
        <a href="{{ route('thread.show', ['id'=>$newcomment->thread->id])}}"><div>{{$newcomment->thread->title}}</div></a>
        {{-- <div class="h-10 truncate whitespace-nowrap overflow-y-hidden">{!! ($newcomment->body) !!}</div> --}}
        <div class="h-10 truncate whitespace-nowrap overflow-y-hidden"><?php echo strip_tags($newcomment->body);  ?></div>
    </div>
    @endforeach
    @endif

 {{-- カテゴリ --}}
@if(isset($category))
<div class="w-auto h-auto bg-white">
    <h2 class="p-2 text-base text-black font-bold text-center">カテゴリ</h2>
    <div class="flex flex-wrap p-2">
        @foreach($category as $one)
        <a href="{{ route('category.show', ['categorynm'=>$one->categorynm])}}"><div class="bg-sky-100 text-xs text-gray-500 rounded py-1 px-2 border-solid border border-sky-300 m-1">{{$one->categorynm}}</div></a>
        @endforeach
    </div>
</div>
@endif

</div>

@endsection