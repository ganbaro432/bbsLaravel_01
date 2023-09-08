@extends('member.m_app')
@section('sectionMetatag')
<style>
 #imgarea img{
    width: 50px;
    height: 50px;
    object-fit: cover; 
 }
</style>
@endsection
@section('content')

<a href="{{url('/member/index')}}">
    <div class="bg-red-600 hover:bg-red-700 p-4 font-bold text-center text-white">
        <h1>削除専用ページ/一覧へ</h1>
    </div>
    </a>


    <!--メインカラムの大枠-->
    <div class="">
        <!--記事一覧-->

        @foreach($articles as $article)
        
        <div class="grid grid-cols-10 border border-b transition-opacity duration-200 ease-out opacity-100 {{$article->open_flg == 0 ? 'bg-sky-100	' : ''}}">
            {{-- 左 --}}
            <div id="imgarea" class="col-span-1">
                @if(isset($article->path))
                {{-- 相対パスと完全パスの使い分け --}}
                <img src="{{ Storage::url($article->path)}}" height="100" width="150">
                {{-- <img src="{{$article->path}}" height="100" width="150">   --}}
                @endif        
            </div>
            {{-- 右 --}}
            <div class="col-span-8">

                {{-- <a href="{{ route('article.show', ['id'=>$article->id])}}"> --}}
                <div class="mb-1 hover:opacity-50 truncate" style="color:#39a9bf">
                    {{-- 公開状態 --}}
                    <form method="post" class="" action="{{ route('member.articleEdit', ['id'=>$article->id])}}">
                     @csrf 
                    @if($article->open_flg ==0)
                    非公開
                    @else
                    公開
                    @endif                    
                    :
                    <input type="hidden" name="id" value="{{$article->id}}" />
                    <input type="submit" class="font-bold text-base cursor-pointer" value="{{$article->title}}" />
                </form>
                </div> 
                {{-- </a>    --}}

                <div class="flex font-light text-sm text-gray-400">
                {{$article->created_at}}/{{$article->open_flg}}
                </div>
            </div>
            {{-- 公開ボタン --}}
            <div class="col-span-1 border border-l-2 flex justify-center items-center hover:opacity-50 bg-black text-white">
                <!--公開非公開切り替え-->
                <form method="post" class="" action="{{ url('articlepublic')}}">
                    @csrf 
                    <input type="hidden" name="id" value="{{$article->id}}" />
                    @if($article->open_flg ==0)
                    <input type="submit" class="font-bold cursor-pointer" value="許可" />
                    @else
                    <input type="submit" class="font-bold cursor-pointer" value="非公開" />
                    @endif
                    
                </form>                
            </div>
        </div>
        

        @if(count($articles) < 1)
        <p>投稿がありません</p>
        @endif

        @endforeach
        <!--記事一覧-->

    </div>
<!--メインカラム-->
@endsection

