 <form method="post" class="border w-full p-2 bg-gray-100" action="{{url('/rep')}}">
        <h1 class="ml-2 py-3 text-gray-600 font-bold">返信を投稿</h1>
        @csrf
        
        <div class="mb-6">
            <input type="text" name="name" value="名無し" placeholder="名前を入力" class="w-2/5 p-2 border placeholder-gray-500 placeholder-opacity-50" />
        </div>
        <div class="mb-3">
            <textarea name="body" placeholder="内容を記入してください" class="w-full h-40 p-2 border placeholder-gray-500 placeholder-opacity-50">>>{{$header->comment_serial}}
</textarea>
        </div>
        <input type="hidden" name="thread_id" value="{{$header->thread_id}}" />
        <input type="hidden" name="rep_id" value="{{$header->id}}" />
        {!! RecaptchaV3::field('register') !!}
        <input type="submit" class="border px-10 py-2 bg-blue-600 text-base text-white font-semibold rounded hover:bg-blue-500" value="返信する" />
    </form>
    <p class="recaptcha_policy">
        This site is protected by reCAPTCHA and the Google
        <a href="https://policies.google.com/privacy">Privacy Policy</a> and
        <a href="https://policies.google.com/terms">Terms of Service</a> apply.
        </p>