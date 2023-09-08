@extends('layouts.app')
@section('content')

@section('sectionMetatag')
{{-- 独自CSS --}}
<link rel="stylesheet" href="{{ asset('css/article.css') }}">
@endsection

		<!--メインカラム-->
		
			<div id="article_body" class="p-4">                    
				<h3 class="">プレビュー確認</h3>
				{{-- タイトル --}}
				<div class="text-xl md:text-2xl font-bold text-gray-800 py-3">
					{{$input['title']}}
				</div>
				{{-- 情報 --}}
				<div class="flex justify-between mb-5 ">
					<div class="mr-3 text-sm text-gray-500">
						**/**/** {{$input['name']}}
					</div>
					<div class="text-2xl">
                   
					</div>
				</div>	
				{{-- 内容 --}}
				<div class="leading-loose">
					{!! $input['body'] !!}
				</div>
			</div>
		

	@endsection