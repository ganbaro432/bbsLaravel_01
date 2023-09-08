@extends('layouts.app')
@section('sectionMetatag')
{!! RecaptchaV3::initJs() !!}
@endsection

@section('content')

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

<!--メインカラム-->

    <div class="px-5 pb-5 pt-3">
        <p class="mt-2 mb-6"><a class="px-2 py-2 bg-blue-500 text-sm text-white font-semibold rounded hover:bg-blue-600" href="{{ route('thread.show', ['id'=>$request->thread_id])}}">戻る</a></p>  

        
        <h2 class="border-b-2 py-2 mb-1 font-bold font-lg">投稿内容の確認<h2>
            <div class="">
                    <div class="my-2">
                        <p class="font-bold">名前</p>
                        {{$request->name}}
                    </div>
                    <div class="my-2">
                        <p class="font-bold">内容 ※改行等は投稿完了後に反映されます</p>
                        <div class="thcombody text-md text-gray-700 mb-2 mt-3">
                            {!! nl2br($request->body) !!}
                        </div>
                    </div>
            </div> 
            
            {{--  --}}
            <form method="post" class="w-full p-2" action="{{url('/res')}}">
                @csrf
                <div class="mb-6 hidden">
                    <input type="text" name="name" value="{{$request->name}}" placeholder="名前を入力" class="w-2/5 p-2 border placeholder-gray-500 placeholder-opacity-50" />
                </div>
                <div class="mb-3 hidden">
                    <textarea name="body" placeholder="内容を記入してください" class="w-full h-40 p-2 border placeholder-gray-500 placeholder-opacity-50">{!! ($request->body) !!}</textarea>
                </div>
                <input type="hidden" name="thread_id" value="{{$request->thread_id}}" />
                <input type="hidden" name="user_id" value="1" />
                {!! RecaptchaV3::field('register') !!}
                <input type="submit" class="border px-10 py-2 bg-blue-600 text-base text-white font-semibold rounded hover:bg-blue-500" value="コメントを投稿する" />
            </form>
            <p class="recaptcha_policy">
            This site is protected by reCAPTCHA and the Google
            <a href="https://policies.google.com/privacy">Privacy Policy</a> and
            <a href="https://policies.google.com/terms">Terms of Service</a> apply.
            </p>
            
    </div>
    




<!--メインカラム-->

@endsection