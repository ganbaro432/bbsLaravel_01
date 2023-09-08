@extends('layouts.app')
@section('content')


<!--メインカラム-->

    <div class="px-5 pb-5 pt-3">
        <p class="mt-2 mb-6"><a class="px-2 py-2 bg-orange-500 text-sm text-white font-semibold rounded hover:bg-orange-600" href="{{ url('/article/index')}}">記事一覧へ</a></p>   

        
            <ul>
                <li class="my-2">
                    <p class="font-bold">必須事項</p>
                    <p>タイトル、名前、本文は必ず入力してください。</p>
                </li>
                <li class="my-2">
                    <p class="font-bold">エディタ機能</p>
                    <p>文字サイズや色、テーブル、リスト等が使用できます。</p>
                    <p><a class="text-sky-600" href="https://qiita.com/Shou_/items/b354b70ceefa05f4263a" rel="noopener noreferrer">解説記事</a> </p>
                </li>
                <li class="my-2">
                    <p class="font-bold">画像投稿</p>
                    <p>1記事につき10ファイルまで、1枚あたり2MB以下のファイルのみ受け付けています。</p>
                </li>
                <li class="my-2">
                    <p class="font-bold">公開までの時間</p>
                    <p>管理人が確認し問題なければ公開します。</p>
                </li>
                <li class="my-2">
                    <p class="font-bold">後から内容を修正するには</p>
                    <p>パスワードを入力すると、後から編集ページにて修正が可能です。パスワードの再発行は行っておりませんので、投稿者の方で記録しておいてください。修正後、記事は一度非公開になります。</p>
                </li>
            </ul>
        <h2 class="border-b-2 py-2 mb-1 font-bold font-lg">投稿前に禁止事項をご確認ください<h2>
            <div class="">
                <ul>
                    <li class="my-2">
                        <p class="font-bold">悪質な内容</p>
                        <p>荒らし行為、わいせつな画像の投稿、犯罪行為に関する投稿など、当掲示板の運営を妨害する行為は禁止しています。</p>
                    </li>
                    <li class="my-2">
                        <p class="font-bold">誹謗中傷</p>
                        <p>嫌がらせや悪口により他人を著しく傷つける内容は、名誉棄損や侮辱にあたる場合があります。当掲示板では禁止です。</p>
                    </li>
                    <li class="my-2">
                        <p class="font-bold">権利侵害</p>
                        <p>画像や文章を投稿する前に、「著作権、肖像権、プライバシー」などの他者の権利を侵害していないことを確認してください。</p>
                    </li>
                </ul>
            </div>         
            
    </div>
    




<!--メインカラム-->

@endsection