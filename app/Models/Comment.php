<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = "comments";
    const UPDATED_AT = null;

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
    public function Like()
    {
        return $this->hasMany(Like::class);
    }
    public function Dislike()
    {
        return $this->hasMany(Dislike::class);
    }

    //IPアドレス生成
    public function idcreat($request)
    {
        $ip = $request->ip();
        $timestamp = date("Ymd");
        $secret = "soau1sLfud73i";

        $id_hash = hash_hmac("sha1", $timestamp . $ip, $secret);
        $id_base64 = base64_encode($id_hash);
        $id = substr($id_base64, 0, 8);
        return $id;
    }

    //コメントURL化
    public static function replaceUrl($text)
    {
        $text = e($text);
        //linuxの改行と合わせる必要がある
        $texts = explode("\r\n", $text);
        //$texts = explode(PHP_EOL, $text); //PHP_EOLは,改行コードをあらわす.改行があれば分割する

        $pattern = '/^https?:\/\/[^\s 　\\\|`^"\'(){}<>\[\]]*$/'; //正規表現パターン
        $replacedTexts = array(); //空の配列を用意

        foreach ($texts as $value) {
            $replace = preg_replace_callback($pattern, function ($matches) {
                //textが１行ごとに正規表現にmatchするか確認する
                if (isset($matches[1])) {
                    return $matches[0]; //$matches[0] がマッチした全体を表す
                }
                //既にリンク化してあれば置換は必要ないので、配列に代入
                return '<a href="' . $matches[0] . '" target="_blank" rel="noopener">' . $matches[0] . '</a>';
            }, $value);
            //1行ごとに改行を付け加えた
            $replacedTexts[] = $replace . "<br>";
            //リンク化したコードを配列に代入
        }
        return implode(PHP_EOL, $replacedTexts);
        //配列にしたtextを文字列にする
    }

    //URLをリッチリンク化を取得
    public static function replaceUrlfull($text)
    {
        $text = e($text);
        //linuxの改行と合わせる必要がある
        $texts = explode("\r\n", $text);
        //$texts = explode(PHP_EOL, $text); //PHP_EOLは,改行コードをあらわす.改行があれば分割する

        $pattern = '/^https?:\/\/[^\s 　\\\|`^"\'(){}<>\[\]]*$/'; //正規表現パターン
        $replacedTexts = array(); //空の配列を用意

        foreach ($texts as $value) {
            $replace = preg_replace_callback($pattern, function ($matches) {
                //textが１行ごとに正規表現にmatchするか確認する
                if (isset($matches[1])) {
                    return $matches[0]; //$matches[0] がマッチした全体を表す
                }

                // url要素
                $url = $matches[0];

                // youtubeリンクか確認
                $youtube_pattern = '/(http(s|):|)\/\/(www\.|)yout(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i';
                if (preg_match($youtube_pattern, $url, $results)) {
                    return '<a class="hover:opacity-75 transition duration-500" href="' . $results[0] . '" target="_blank" rel="noopener"><img src="https://img.youtube.com/vi/' . $results[6] . '/mqdefault.jpg" alt="youtube"><p class="text-sm font-normal">' . $results[0] . '</p></a>';
                }

                
                // リッチリンク作成
                $title = '';
                // $description = '';

                $url = $matches[0];
                /*
                $ch = curl_init($url); // urlは対象のページ
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // exec時に出力させない
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // リダイレクト許可
                curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // 最大リダイレクト数
                $html = curl_exec($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if (empty($html)) {
                    return '<a href="' . $matches[0] . '" target="_blank" rel="noopener">' . $matches[0] . '</a>';
                }

                $dom_document = new \DOMDocument();

                
                $dom_document->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'auto'));

                $xml_object = simplexml_import_dom($dom_document);

                $og_title_xpath = $xml_object->xpath('//meta[@property="og:title"]/@content');
                // $og_description_xpath = $xml_object->xpath('//meta[@property="og:description"]/@content');

                if (!empty($og_title_xpath)) {
                    $title = (string)$og_title_xpath[0];
                } else {
                    return '<a href="' . $matches[0] . '" target="_blank" rel="noopener">' . $matches[0] . '</a>';
                };

                // if ( ! empty($og_description_xpath))
                // {
                //     $description = (string)$og_description_xpath[0];
                // }else{
                //     return '<a href="' . $matches[0] . '" target="_blank" rel="noopener">' . $matches[0] . '</a>';
                // };

                //ogpがないとエラーになる
                // return '<div class="border bg-orange-50 p-3 mt-3 rounded"><a class="text-sm" href="' . $url .'" target="_blank" rel="noopener">' . $title .'<p class="text-xs font-light">' . $url . '</p> </a> <p class="text-xs text-gray-500 font-light mt-2">' . $description .'</p></div>';
                // return '<div class="border bg-orange-50 p-3 mt-3 rounded"><a class="text-sm" href="' . $url . '" target="_blank" rel="noopener">' . $title . '<p class="text-xs font-light">' . $url . '</p> </a></div>';
                */

                return '<a href="' . $matches[0] . '" target="_blank" rel="noopener">' . $matches[0] . '</a>';
            }, $value);
            //1行ごとに改行を付け加えた
            $replacedTexts[] = $replace . "<br>";
            //リンク化したコードを配列に代入
        }
        return implode(PHP_EOL, $replacedTexts);
        //配列にしたtextを文字列にする
    }


    // ngワードチェックいれとく
    public static function ngwordCheck($text)
    {
        $NG_WORDS = [
            "ホロリス",
            "日刊ホロライブ",
            "キショ",
            "ホロライブさん",
            "臭いリスナー",
            "にじガー",
            "箱もファン",
            "ホロライブの信者",
            "ホロライブリスナー",
            "ホロライブのリスナー",
            "ホロ信",
            "ホロのリスナー",
            "気持ち悪いの多い",
            "マナー悪いリスナー",
            "ホロライブの客",
            "同接数でしか",
            "迷惑行為",
            "バカ事務所",
            "パクリ",
            "パクり",
            "にじガー",
            "パクる",
            "どうせ信者",
            "パクアイドル",
            "鼻つまみ者",
            "幌リス",
            "気持ち悪いの多い",
            "ホロ側の人",
            "ホロライブの文化",
            "同接買",
            "ホ.ロリス",
            "本当にチョロい",
            "数字至上主義連中",
            "ポロライブ",
            "信者発狂",
            "前科持ち",
            "倫理観ゴミ",
            "嫌われてる箱",
            "嫌われ事務所",
            "パクった",
            "カバーの社風",
            "ドル売り箱",
            "お馴染みのホロライブ",
            "被害者ヅラ",
            "弱男向け箱",
            "発狂される",
            "厄介なリスナー",
            "発狂するアホ",
            "ホロのオタク",
            "箱信者",
            "イナゴ行為",
            "黙認ベース",
            "長文で発狂",
            "スパチャ稼ぎ",
            "嫌われてる事務所",
            "キャバクラ商売",
            "無許諾",
            "倫理観0",
            "倫理観ゼロ",
            "業界の癌",
            "魂垢",
            "ハゲリス",
            "コンプライアンス無視",
            "holoリス",
            "バカのホロ",
            "叩かれる事務所",
            "迷惑型",
            "迷惑大手",
            "ホロさん",
            "ふたば",
            "ブッダ",
            "悪質まとめ"
        ];

        // 判定
        foreach ($NG_WORDS as $word) {
            // 禁止用語が含まれていれば
            if (strpos($text, $word) !== false) {
                return false;
            }
        }
        return true;
    }
}
