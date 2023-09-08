<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Articleimage;
use App\Models\Comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;



class ArticleController extends Controller
{
    //
    // 投稿を表示、新設
    private $formItems = ["body", "title", "name", "category", "password", "data", "id"];
    // エンティティ、タグを限定
    public function filter_text($text, $allow_tags = array()) {

        $tags = implode('|', $allow_tags);
        $attr = '(?: ++[\\w-]++(?:=(?:[\\w-]++|"[^"]*+"|\'[^\']*+\'))?+)';
        $keep = $tags !== '' ?
            "</(?:{$tags}) *+>|<(?:{$tags}){$attr}*+ *+/? *+>|":
            ''
        ;
        $pattern = "@{$keep}(</[\\w-]++ *+>|<[\\w-]++{$attr}*+ *+/? *+>|<[^<>]*+>|[<>])@i";
        return preg_replace_callback($pattern, function ($matches) {
            switch (true) {
                case isset($matches[1]): return '';
                default:                 return $matches[0];
            }
        }, $text);   
    }


     //画像をエディタから受信、一時フォルダに保存
     public function image(Request $request){

        $result = $request->file('file')->isValid();
        if($result){
            // ファイルサイズ
            $filesize = $request->file('file')->getSize();

            // varid、下でもどっちでもいい、gifは受け付けない
            $request->validate([
                'file' => 'max:2048|mimes:jpg,jpeg,png'
            ],
            [
                'file.max' =>'2MB以下のファイルを指定してください',
                'file.mimes' =>'jpg,jpeg,pngのいずれかのファイルを指定してください'
            ]);

            // ファイル名をランダムにしたい
            $str = 'abcdefghijklmnopqrstuvwxyz';
            $rand_str = substr(str_shuffle($str), 0, 4);
            $extension = $request->file->getClientOriginalExtension();
            $filename = date("YmdHis").$rand_str.'.'.$extension;

            // resize,ImageManager
            $resized = Image::make($request->file('file'));
            $resized->orientate();
            $resized->resize(300, 300,
            function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // $dt = Carbon::now();
            // // $year= $dt->year;
            // // $month = $dt->month;
            
            // $filePath = storage_path('app/'.$savepath);
            // if(!Storage::exists('public/'.$savepath)) {
            //     Storage::makeDirectory('public/'.$savepath, 0755, true); 
            // }
            $savepath = 'article';
            $resized->save('storage/'.$savepath . '/' . $filename);
 

            // 相対パスだとエラーが出る、URLはボディで直接表示するsrc
            $public = asset('/');
            $publicpath = $public.'storage/'.$savepath.'/'.$filename;
            // DBに保存するようのパスを用意、削除時に参照
            $dbpath = $savepath.'/'.$filename;
            $response = [
                'url' => $publicpath,
                'filename' => $filename,
                'dbpath' =>$dbpath,
            ];
            return response()->json($response);

        }
    }

    // プレビュー画面へ
    public function preview(Request $request){
        $input = $request->only($this->formItems);
        
        $text= $input['body'];    
        $allow_tags = array("h1","h2","h3","h4","h5","h6","p","b","u","span","font","br","ul","li","img","pre","blockquote", "table", "tbody", "tr", "td", "a");
        $input['body'] = $this->filter_text($text, $allow_tags);   

        if(!$input){
            return redirect()->route("article.form");
        }
        return view("article.article_preview", ["input" => $input]);
    }    

    // エディタから、保存
    public function post(Request $request){
        // 内容から保存
        $article = new Article();
        $request->validate([
            'body' => 'required|string',
            'title' => 'required|string',
            'name' => 'required|string'
         ],
         [
            'article.required' => '内容を入力してください',
            'title.required' => 'タイトルを入力してください',
            'name.required' => '名前を入力してください'
         ]);  

        //  限定
        $input = $request->only($this->formItems);
        // エンティティ
        $text= $input['body'];    
        $allow_tags = array("h1","h2","h3","h4","h5","h6","p","b","u","span","font","br","ul","li","img","pre","blockquote", "table", "tbody", "tr", "td", "a");
        $input['body'] = $this->filter_text($text, $allow_tags);          
         
        //  保存
        DB::beginTransaction();
        try{

            $article->body = $input['body'];
            $article->title = $input['title'];
            $article->name = $input['name'];
            $article->category_id = $input['category'];
            $article->ip = $request->ip();
            // パスワードはハッシュ化
            $password = $input['password'];
            $article->password = Hash::make($password);
            $article->save();
            
            // 日付ファイルに保存したい
            if($request->has('data')){
                $image = $request->data;
                foreach($image as $key){
                    $img = new Articleimage();
                    $img->path = $key;
                    $img->article_id = $article->id;
                    $img->save();
                }
            }
    
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
        }

        return view("article.article_complete");
    }

    // 記事投稿一覧画面を表示
    public function index(){
        // 最新コメントの取得
        $newcomments = Comment::with('thread')->orderBy('created_at', 'desc')->limit(5)->get();  
        
        $subSQL = Articleimage::select(['article_id' , DB::raw('min(path) as path')])
        ->groupBy('article_id');
        $articles = Article::select('articles.id', 'articles.created_at', 'articles.title', 'articles.category_id', 'articleimages.path', 'categories.categorynm')
        ->leftJoinSub($subSQL, 'articleimages', 'articles.id', '=', 'articleimages.article_id')
        ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')        
        ->where('articles.open_flg', '=', 1)
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        // カテゴリ
        $category = Category::select('id', 'categorynm')
        ->get();

        return view("article.article_index", compact('articles','newcomments', 'category'));
    }
        // 記事詳細画面
    public function show($id){
        // 最新コメントの取得
        $newcomments = Comment::with('thread')->orderBy('created_at', 'desc')->limit(5)->get();  
        
        $content = Article::select('articles.id', 'articles.body', 'articles.name', 'articles.created_at', 'articles.title', 'articles.category_id', 'articles.open_flg', 'categories.categorynm')
        ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')   
        ->where('articles.id', '=', $id)
        ->first();
 
        // $content = Article::find($id);
        return view('article.article_content', compact('content', 'newcomments'));
    }

    // 記事編集画面に映る前にパスワード入力画面へ
    public function checkpage($id){
        return view('article.article_password', compact('id'));
    }
    // パスワード確認し編集画面へ
    public function ariclePassCheck(Request $request){
        $id = $request->id;
        $form_pass = $request->password;

        // dbから取得
        $content = Article::find($id);
        $db_pass = $content->password;
        if(Hash::check($form_pass, $db_pass)){
            return view('article.article_edit', compact('content'));
        } else {
            return back()->with('result', 'パスワードが違います');
        }
    }

    // 記事編集画面
    public function edit($id) {
        $content = Article::find($id);
        return view('article.article_edit', compact('content'));
    }

    // 記事アップデート保存
    public function update(Request $request){
        $request->validate([
            'body' => 'required|string',
            'title' => 'required|string',
            'name' => 'required|string'
         ],
         [
            'body.required' => '内容を入力してください',
            'title.required' => 'タイトルを入力してください',
            'name.required' => '名前を入力してください'
         ]);  

        //  requestから取得
        $input = $request->only($this->formItems);
        $id = $request->id;
        $form_pass = $request->password;
        // パスワードチェック
        $content = Article::find($id);
        $db_pass = $content->password;
        if(!Hash::check($form_pass, $db_pass)){
            $message = "パスワードが違います";
            return view('article.article_edit')->with(compact('message', 'content'));
        } 

        // エンティティ
        $text= $input['body'];    
        $allow_tags = array("h1","h2","h3","h4","h5","h6","p","b","u","span","font","br","ul","li","img","pre","blockquote", "table", "tbody", "tr", "td", "a");
        $input['body'] = $this->filter_text($text, $allow_tags);          
         
        // 保存
        DB::beginTransaction();
        try{
            Article::where('id', '=', $id)->update([
                'body' => $input['body'],
                'title' => $input['title'],
                'name' => $input['name'],
                'category_id' => $input['category'],
                'open_flg' => false,
            ]);

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
        }

        return view("article.article_complete");
    }

    // 記事削除
    public function delete($id) {
        // 画像本体を削除
        $imgs = Articleimage::where('article_id', '=', $id)
        ->get();
        // 画像削除
        foreach($imgs as $img){
            $path = $img->path;
            Storage::disk('public')->delete($path);
        }

        DB::beginTransaction();
        try{
            // 画像パスがリレーションで削除されるから、DBは最期に削除
            $article = Article::find($id);
            $article->delete();   

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
        }
        return view("article.article_complete");
    }    

    // 記事カテゴリ一覧ページ

    
}
