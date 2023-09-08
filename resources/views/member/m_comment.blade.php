@extends('member.m_app')
@section('sectionTitle')
{{$header->thread->title}}
@endsection

@section('content')

<!--メインカラム-->

        <!--アラート-->
        @if(session('message'))
        <div class="m-4 p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800">{{session('message')}}</div>
        @endif
        @if ($errors->any())
            <div class="m-4 p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    <div class="bg-red-600 p-4 font-bold text-center text-white">
        <h1>管理人専用返信ページ</h1>
    </div>
    
    <div class="px-5 pb-5 pt-3 border-b-2">
        <h1 class="font-bold text-xl text-gray-700 mb-3">{{$header->thread->title}}<h2>
        <p class="font-light text-sm text-gray-500">
          {{$header->thread->created_at}}
        </p>
    </div>
    <!--元スレ-->

    <!--返信元コメント-->

    <div class="border-b-2 px-5 pb-5 pt-3">
        <!--情報欄-->
        <div class="flex font-light text-xs text-gray-500 mb-3">
         {{$header->comment_serial}}.
         {{$header->name}} {{$header->created_at}} ID:{{$header->user_id}}
        </div>
         <!--情報欄-->
         <div id="thcombody" class="font-bold text-xl text-gray-700">{!! $header->body !!}</div>
    </div>
    <!--返信元-->

    <form method="post" class="border w-full p-2 bg-gray-100" action="{{url('/kanrirep')}}">
        <h1 class="ml-2 py-3 text-gray-600 font-bold">管理人から返信を投稿</h1>
        @csrf
        
        <div class="mb-6">
            <input type="text" name="name" value="管理人" placeholder="名前を入力" class="w-2/5 p-2 border placeholder-gray-500 placeholder-opacity-50" />
        </div>
        <div class="mb-3">
            <textarea name="body" placeholder="内容を記入してください" class="w-full h-40 p-2 border placeholder-gray-500 placeholder-opacity-50">>>{{$header->comment_serial}}
</textarea>
        </div>
        <input type="hidden" name="thread_id" value="{{$header->thread_id}}" />
        <input type="hidden" name="rep_id" value="{{$header->id}}" />
        <input type="hidden" name="kanriflag" value="1"  />
        
        <input type="submit" class="border px-10 py-2 bg-white text-base text-gray-700 font-semibold rounded hover:bg-gray-100" value="返信する" />
    </form>

    
    
    
    <!--メインカラムの大枠-->
    <div class="">

        <!--記事一覧-->
        @foreach($comments as $comment)
        <div class="border-b-2 px-5 pb-5 pt-3">
            
            
            
            <!--情報欄-->
            <div class="flex font-light text-xs text-gray-500 mb-3">
             {{$comment->comment_serial}}.
             {{$comment->name}} {{$comment->created_at}} ID:{{$comment->user_id}}
             
             
            </div>
             <!--情報欄-->

             <div id="thcombody" class="font-bold text-xl text-gray-700 break-words">{!! $comment->body !!}</div>
             <div class="text-right">

                </div>

        </div>
        @endforeach
        <!--記事一覧-->

        {{$comments->links()}}
    </div>
    <!--メインカラムの大枠-->




<!--メインカラム-->

@endsection