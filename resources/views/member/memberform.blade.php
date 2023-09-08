@extends('member.m_app')
@section('content')



<!--メインカラム-->

	<!--エラーメッセージ-->
	@if ($errors->any())
	<div class="text-red-400 font-bold">
	<ul>
	@foreach ($errors->all() as $error)
	<li>{{ $error }}</li>
	@endforeach
	</ul>
	</div>
	@endif

    <h1 class="border-b-2 ml-2 py-3 text-gray-600 font-bold">削除ボランティアログイン</h1>
    <form method="post" class="w-full p-2" action="{{ url('vlogin') }}">
        @csrf
        
        
        <div class="mb-6">
            <input type="text" name="user_id" value="" placeholder="ID" class="w-2/5 py-2 border placeholder-gray-500 placeholder-opacity-50" />
        </div>
		<div class="mb-3">
            <input type="password" name="password" value="" placeholder="パスワード" class="w-2/5 py-2 border placeholder-gray-500 placeholder-opacity-50" />
        </div>
        
        <input type="submit" class="border px-10 py-2 bg-white text-base text-gray-700 font-semibold rounded hover:bg-gray-100" value="ログイン" />
    </form>

    <!--メインカラム-->
@endsection