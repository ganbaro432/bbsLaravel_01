$(function () {
    $patternrep = '/(&gt;&gt;[0-9]{1,3})/';
    $patternrep2 = '/(<a(?: .+?)?>)(&gt;&gt;[0-9]{1,3})(<\/a>)/';
    $replace2 = "\${2}";
    $urlpattern = /(https|http):\/\/(twitter.com)\/([A-Za-z0-9_]*)\/(status|statues)\/(\d+)/;
    // twitterURLだけとaタグ限定で分ける
    $twitterpattern = '/(https|http):\/\/twitter.com\/([A-Za-z0-9_]*)\/(status|statues)\/(\d+)/';
    $patternget = '/(<a(?: .+?)?>)(https|http):\/\/twitter.com\/([A-Za-z0-9_]*)\/(status|statues)\/(\d+)(.*?)(<\/a>)/';
    // $patternget2 = '/(?<=<div>)(.*?)(?=<\/div>)/';
    $('.rescontent').each(function () {
        let text = $(this).html();
        $twitterUrl = text.match(/(https|http):\/\/twitter.com\/([A-Za-z0-9_]*)\/(status|statues)\/(\d+)/);
        if ($twitterUrl) {
            $tweetTag = '<blockquote class="twitter-tweet"><a href="' + $twitterUrl[0] + '"></a></blockquote>';

            let atag = text.replace(/(<a(?: .+?)?>)(https|http):\/\/twitter.com\/([A-Za-z0-9_]*)\/(status|statues)\/(\d+)(.*?)(<\/a>)/, $tweetTag + '$&');
            // console.log($atag);
            $(this).html(atag);
            // $(this).append($tweetTag);
        }
    });

});

$(document).ready(function () {

    // main
    window.twttr = (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0],
            t = window.twttr || {};
        if (d.getElementById(id)) return t;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://platform.twitter.com/widgets.js";
        fjs.parentNode.insertBefore(js, fjs);

        t._e = [];
        t.ready = function (f) {
            t._e.push(f);
        };
        return t;
    }(document, "script", "twitter-wjs"));

    // html取得
    let text = $(this).html();
    twttr.widgets.load(text);
});