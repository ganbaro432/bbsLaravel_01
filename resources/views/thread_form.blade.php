@extends('layouts.app')
@section('sectionMetatag')
<meta property="og:type" content="website">
<meta property="og:title" content="掲示板">
<meta property="og:site_name" content="掲示板">
<meta property="og:url" content="{{ url()->current() }}">
<meta name="description" content="コメント。">
<meta name="twitter:description" content="コメント。">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    {!! RecaptchaV3::initJs() !!}

@section('content')

<!--メインカラム-->

    <!--アラート-->
    @if ($errors->any())
	    <div class="m-4 p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li>{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif

    <h1 class="border-b-2 ml-2 py-3 text-gray-600 font-bold">スレッドを投稿する</h1>
    <form method="post" class="w-full p-2" action="{{url('/create')}}">
        @csrf
        
        <div class="mb-3">
            <input type="text" name="title" value="" placeholder="スレッドタイトルを記入してください" class="w-full py-2 border placeholder-gray-500 placeholder-opacity-50" />
        </div>
        <div class="mb-3">
            <textarea name="body" placeholder="内容を記入してください" class="w-full h-40 py-2 border placeholder-gray-500 placeholder-opacity-50"></textarea>
        </div>
        <div class="mb-6">
            <select name="category" class="border p-2">
                <option value="0">カテゴリを選択</option>
                <option value="1">1</option>
                <option value="2">2</option>
            </select>
            </div>
        <div class="mb-6">
            <input type="text" name="name" value="名無し" placeholder="名前を入力" class="w-2/5 py-2 border placeholder-gray-500 placeholder-opacity-50" />
        </div>
        
        
        {!! RecaptchaV3::field('register') !!}
        <input type="submit" class="border px-10 py-2 bg-blue-600 text-base text-white font-semibold rounded hover:bg-blue-500" value="スレッドを投稿する" />
    </form>
    <p class="recaptcha_policy">
        This site is protected by reCAPTCHA and the Google
        <a href="https://policies.google.com/privacy">Privacy Policy</a> and
        <a href="https://policies.google.com/terms">Terms of Service</a> apply.
    </p>

    <!--メインカラム-->
@endsection