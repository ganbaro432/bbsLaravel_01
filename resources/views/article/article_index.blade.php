@extends('layouts.app')

@section('sectionMetatag')
<style>
 #imgarea img{
    width: 150px;
    height: 80px;
    object-fit: cover; 
 }
</style>
@endsection

@section('content')



<p class="text-xl text-center p-4 font-bold bg-orange-500 text-white"><i class="fa-solid fa-book-open mr-2"></i>記事一覧ページ</p>
<div class="grid grid-cols-3 mb-2 border-y">
    <!--urlに応じて変更-->
    <a href="{{ url('/')}}" class="py-4 text-center text-gray-700"><div class="border-r border-gray-400">掲示板</div></a>

    <a href="{{ url('/article')}}" class="py-4 text-center text-gray-700"><div class="border-r border-gray-400"><i class="fa-solid fa-file-pen mr-1"></i>記事投稿</div></a>

    <a href="{{url('/help')}}" class="py-4 text-center text-gray-700"><div class=""><i class="fa-solid fa-comments mr-1"></i>Q&A</div></a>
</div> 



    <!--メインカラムの大枠-->
    <div class="p-3">
{{-- 記事リスト --}}
@foreach($articles as $article)

        <a href="{{ route('article.show', ['id'=>$article->id])}}">

        <div class="border-b p-2 mb-3 transition-opacity duration-200 ease-out opacity-100 hover:opacity-50">
            <div class="">
                <!--タイトル-->
                <div class="font-bold md:text-lg text-base mb-5 truncate" style="color:#39a9bf">
                    {{$article->title}}
                </div>  
                {{-- 情報 --}}
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
        
 
        </a>
        @if(count($articles) < 1)
        <p>投稿がありません</p>
        @endif

@endforeach
        <!--記事一覧-->


        

    @if($articles->hasPages())
    <div class="flex justify-center p-3 mb-4 mt-2">{!! $articles->links('pagination::original_pagination_view') !!}</div>
    @endif

    {{-- カテゴリ --}}
    {{-- <div>
        @if(isset($category))
        <div class="w-auto h-auto">
            <h2 class="p-2 text-base text-black font-bold text-center">記事カテゴリ</h2>
            <div class="flex flex-wrap p-2">
                @foreach($category as $one)
                <a href=""><div class="bg-sky-100 text-md text-gray-700 rounded py-1 px-2 border-solid border border-sky-300 m-1">{{$one->categorynm}}</div></a>
                @endforeach
            </div>
        </div>
        @endif
    </div> --}}
        
        

    </div>
<!--メインカラム-->
@endsection

<!--記事一覧だけのサイド-->
@section('sides')



{{-- サイド最新コメント --}}
<div class="w-auto h-auto bg-white">
    <h2 class="p-4 text-base text-black font-bold text-center">最新コメント</h2>
    @if(isset($newcomments))
    @foreach($newcomments as $newcomment)
    
    <div class="newcomment mb-2 p-2 border-b font-light text-sm">
        <a href="{{ route('thread.show', ['id'=>$newcomment->thread->id])}}"><div>{{$newcomment->thread->title}}</div></a>
        {{-- <div class="h-10 truncate whitespace-nowrap overflow-y-hidden">{!! ($newcomment->body) !!}</div> --}}
        <div class="h-10 truncate whitespace-nowrap overflow-y-hidden"><?php echo strip_tags($newcomment->body);  ?></div>
    </div>
    @endforeach
    @endif
</div>



@endsection