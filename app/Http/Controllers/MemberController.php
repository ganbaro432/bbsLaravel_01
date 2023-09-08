<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Articleimage;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Thread;
use App\Models\Comment;
// use App\Models\Deletelog;
use Illuminate\Support\Facades\DB;
use App\Models\Commentcounter;
use Illuminate\Support\Facades\Storage;


class MemberController extends Controller
{
    //スレッド一覧
    function index(){
        $threads = Thread::all();
                
        // 削除予定を表示
        // 999超えたスレッド
        $fullthreads = Thread::select('threads.id', 'threads.title', 'comment_counter.counter')
        ->leftJoin('comment_counter', 'threads.id', '=', 'comment_counter.thread_id')
        ->where('comment_counter.counter', '>', '999')
        ->get();
        // 削除予定の準備期間、3週間レスがない
        $subSQL1 = Comment::select(['thread_id', DB::raw('max(created_at) as upd')])
        ->groupBy('thread_id');
        $poorthreads = Thread::select('threads.id', 'threads.title', 'comments.upd', 'comment_counter.counter')
        ->leftJoinSub($subSQL1, 'comments', 'threads.id', '=', 'comments.thread_id')
        ->leftJoin('comment_counter', 'threads.id', '=', 'comment_counter.thread_id')
        ->where('comments.upd', '<=', date('Y/m/d', strtotime('-3 week', time())))
        ->where('comment_counter.counter', '<', '20')
        ->get();
        // 4週間レスがない、レス数の条件もいる
        $subSQL2 = Comment::select(['thread_id', DB::raw('max(created_at) as upd')])
        ->groupBy('thread_id');
        $oldthreads = Thread::select('threads.id', 'comments.upd', 'threads.title', 'comment_counter.counter')
        ->leftJoinSub($subSQL2, 'comments', 'threads.id', '=', 'comments.thread_id')
        ->leftJoin('comment_counter', 'threads.id', '=', 'comment_counter.thread_id')
        ->where('comments.upd', '<=', date('Y/m/d', strtotime('-4 week', time())))
        ->where('comment_counter.counter', '<', '20')
        ->get();        
        return view("member.m_thread_list", compact('threads','fullthreads', 'poorthreads', 'oldthreads'));
    }
    //ソート
    function sort($sort){

        $flag = $sort;
        $order = 'asc';

        // 999超えたスレッド
        $fullthreads = Thread::select('threads.id', 'threads.title', 'comment_counter.counter')
        ->leftJoin('comment_counter', 'threads.id', '=', 'comment_counter.thread_id')
        ->where('comment_counter.counter', '>', '999')
        ->get();
        // 2週間レスがなく、レス数が5以下
        $subSQL1 = Comment::select(['thread_id', DB::raw('max(created_at) as upd')])
        ->groupBy('thread_id');
        $poorthreads = Thread::select('threads.id', 'threads.title', 'comments.upd', 'comment_counter.counter')
        ->leftJoinSub($subSQL1, 'comments', 'threads.id', '=', 'comments.thread_id')
        ->leftJoin('comment_counter', 'threads.id', '=', 'comment_counter.thread_id')
        ->where('comments.upd', '<=', date('Y/m/d', strtotime('-2 week', time())))
        ->where('comment_counter.counter', '<', '6')
        ->get();
        // 4週間レスがない
        $subSQL2 = Comment::select(['thread_id', DB::raw('max(created_at) as upd')])
        ->groupBy('thread_id');
        $oldthreads = Thread::select('threads.id', 'comments.upd', 'threads.title')
        ->leftJoinSub($subSQL2, 'comments', 'threads.id', '=', 'comments.thread_id')
        ->where('comments.upd', '<=', date('Y/m/d', strtotime('-4 week', time())))
        ->get();  

        if($flag == 'new'){
            $flag = 'created_at';
            $order = 'desc';

            $threads = Thread::select('threads.*', 'categories.categorynm')
            ->leftJoin('categories', 'threads.category_id', '=', 'categories.id')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            $counter = CommentCounter::paginate(50);

        };

        if($flag == 'update'){

            $subSQL = Comment::select(['thread_id', DB::raw('max(created_at) as upd')])
            ->groupBy('thread_id');
            $threads = Thread::select('threads.*', 'categories.categorynm')
            ->leftJoinSub($subSQL, 'c', 'c.thread_id', 'threads.id')
            ->leftJoin('categories', 'threads.category_id', '=', 'categories.id')
            ->orderBy('upd', 'desc')
            ->paginate(50);
            $counter = CommentCounter::paginate(50);
        }      
        return view("member.m_thread_list", compact('threads','counter','fullthreads', 'poorthreads', 'oldthreads'));  
    }

    //スレッド詳細
    function show($id, $sort = null){
        $header = Thread::find($id);
        $flag = $sort;
        
        if(!isset($flag)){
            // $comments = Comment::where('thread_id', $id)->get();
            $comments = Comment::where('thread_id', $id)->where([['kanriflag', '!=', 2]])->orderBy('created_at', 'desc')->get();
        } elseif($flag == 'new'){
            $comments = Comment::where('thread_id', $id)->where([['kanriflag', '!=', 2]])->orderBy('created_at', 'desc')->get();
        }else{
            $comments = Comment::where('thread_id', $id)->where([['kanriflag', '!=', 2]])->orderBy('created_at', 'asc')->get();
        };
        return view('member.m_thread', compact('header', 'comments'));
        
    }

    //アップデート処理
    // 
    function update(Request $request){

        DB::beginTransaction();
        try{
            $input = $request->only('id', 'thread_id', 'comment_body', 'kanriflag');
            $userid = $request->session()->get('simple_auth');

            //削除文で更新
            $comment = Comment::find($input['id']);
            // $comment->body = '削除しました';
            // $comment->name = '削除しました';
            $comment->kanriflag = $input['kanriflag'];

            $comment->save();

            //deletelogに保存
            // 20221112 管理フラグで非表示を導入したのでログはいらない
            // $log = new Deletelog();
            // $log->thread_id = $input['thread_id'];
            // $log->comment_id = $input['id'];
            // $log->member_name = $userid;
            // $log->comment_body = $input['comment_body'];
            // $log->save();
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return back();
    }

    // スレッド削除確認ページへ
    function thdelconfirm($id){
        $header = Thread::find($id);
        return view('member.m_thdel', compact('header'));
    }
    // スレッド削除
    function thdel(Request $request){
        DB::beginTransaction();
        try{
            $input = $request->only('id');
            $id = $input['id'];
            $thread = Thread::find($id);
            $thread->delete();
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect('/member/index');
    }

    //管理人用コメント詳細表示
    // 
   function repshow($id){
    //1件だけならfind
    $header = Comment::find($id);
    $comments = Comment::where('rep_id', $id)->paginate(10);

    return view('member.m_comment', compact('header', 'comments'));
    }

    //管理人用返信コメント登録 コメント番号就く前のスレッドはエラーがでる
    function rep(Request $request){

        $request->validate([
            'name' => 'required|string|max:20',
            'body' => 'required|string|max:800',
        ],
        [
            'name.required' =>'名前を入力してください',
            'name.max' =>'20文字以内で名前を入力してください',
            'body.required' =>'内容を入力してください',
            
        ]);

        DB::beginTransaction();
        try{
            $input = $request->only('thread_id', 'name', 'body', 'rep_id', 'kanriflag');

            $entryRep = new Comment();
            $entryRep->user_id = $entryRep->idcreat($request);
            $entryRep->thread_id = $input['thread_id'];
            $entryRep->name = $input['name'];
            //コメントURL化メソッドをためす
            $entryRep->body = $entryRep->replaceUrl($input['body']);
            $entryRep->rep_id = $input['rep_id'];
            $entryRep->kanriflag = $input['kanriflag'];

            $select = Commentcounter::where('thread_id',$request->thread_id);
            $i = $select->first('counter')->counter;
            $entryRep->comment_serial = $i + 1;
            $entryRep->save();

            $updateCounter = new CommentCounter();
            $updateCounter->counter = $select->increment('counter');
            $updateCounter->update();

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return back()->with('message', '返信を投稿しました');
    }

    // 検索
    // 
    function search(Request $request){
        $request->validate(([
            'keyword' => 'required'
        ]));
        $keyword = $request->input('keyword');
        $query = Thread::query();
        $query->where('title', 'LIKE', "%{$keyword}%");
        $posts = $query->paginate(50);

        // 最新コメントの取得
        $newcomments = Comment::with('thread')->orderBy('created_at', 'desc')->limit(5)->get();

        return view("member.m_search", compact('posts', 'keyword', 'newcomments'));
    }


    // 記事専用関数 
    // 
    // 取得データを限定
    private $formItems = ["body","title", "name", "category", "password", "data", "id"];
    // エンティティ
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

    // 記事一覧
    function article(){
        $subSQL = Articleimage::select(['article_id' , DB::raw('max(path) as path')])
        ->groupBy('article_id');

        $articles = Article::select('articles.id', 'articles.created_at', 'articles.title', 'articles.category_id', 'articles.open_flg', 'articleimages.path')
        ->leftJoinSub($subSQL, 'articleimages', 'articles.id', '=', 'articleimages.article_id')
        ->orderBy('created_at', 'desc')
        ->get();

        return view("member.m_article", compact('articles'));
    }
    // 記事公開、非公開
    function public(Request $request){
        $input = $request->only('id');

        //公開状態か否か判定
        $article = Article::find($input['id']);
        $open_flg = $article->open_flg;
        if($open_flg == 1){
            $article->open_flg = false;
        } else {
            $article->open_flg = true;
        }
        
        $article->save();

        return back();
    }
    // 管理人用編集画面
    function edit($id){
        $content = Article::find($id);
        return view('member.m_articleEdit', compact('content'));
    }
    // 管理人用記事アップデート保存
    public function updateArticle(Request $request){
        $article = new Article();
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

        //  限定
        $input = $request->only($this->formItems);
        // エンティティ
        $text= $input['body'];    
        $allow_tags = array("h1","h2","h3","h4","h5","h6","p","b","u","span","font","br","ul","li","img","pre","blockquote","iframe", "table", "tbody", "tr", "td", "a");
        $input['body'] = $this->filter_text($text, $allow_tags);          
            
        //  保存
        $id = $request->id;

        DB::beginTransaction();
        try{
            Article::where('id', '=', $id)->update([
                'body' => $input['body'],
                'title' => $input['title'],
                'name' => $input['name'],
                'category_id' => $input['category'],
            ]);
            // 画像があればパスに保存
            if($request->has('data')){
                $image = $request->data;
                foreach($image as $key){

                    $img = new Articleimage();
                    $img->path = $key;
                    $img->article_id = $id;
                    $img->save();
                }
            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
        }

        return view("article.article_complete");
    }

    // 記事削除
    public function deleteArticle($id) {
        // 画像本体を削除
        $imgs = Articleimage::where('article_id', '=', $id)
        ->get();
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

    // 使われていない2日前の画像を取得する共通関数
    public function trash_list(){
       // フォルダに保存されているファイル
       $storage_image = Storage::disk('public')->allFiles();
       // 画像ファイルだけ
       $check_image = array();

       foreach($storage_image as $img){
           // 拡張子をチェック
           $extension = pathinfo($img, PATHINFO_EXTENSION);
           if($extension == "jpg" || $extension == "png" || $extension == "jpeg"){
               // 投稿日時を知りたい
               $post_time = Storage::disk('public')->lastModified($img);
               // 2日前のファイルのみに変更するように
               $limit_time = strtotime('yesterday');
               if($post_time < $limit_time){
                   $check_image[] = $img;
               }  
               // $check_image[] = $img;
           }
       }
       
       // DBに保存されている画像
       $articleimage = Articleimage::select('path')
       ->get();
       // DBから取得したオブジェクトを連想配列に
       $db_image = array();
       foreach($articleimage as $img){
           $db_image[] = $img->path;
       }
       // 差分を表示
       $trash_image = array_diff($check_image, $db_image);
       return $trash_image;
    }

    // 使われていない記事画像リストを表示
    public function articleImage(){

        $trash_image = $this->trash_list();
        return view("member.m_articleImg", compact('trash_image'));
    }

    // 使われていない記事画像リスト、2日前分を一括削除
    function articleImageDelete(){
        $trash_image = $this->trash_list();
        // 削除
        if($trash_image){
            foreach($trash_image as $path){
                Storage::disk('public')->delete($path);
            }
        }
        return redirect('/member/index');
    }

    // ボランティアリストページ表示
    public function memberList(){

        $members = Member::select('id', 'user_id', 'user_name')
        ->get();
        return view("member.m_memberList", compact('members'));
    }
    // ボランティアリスト登録
    public function memberRegist(Request $request){
        $request->validate([
            'userid' => 'required|string|max:20',
            'username' => 'required|string|max:20',
            'pass' => 'required|string|max:20',
            'check' => 'required|string|max:20'
        ]);

        $input = $request->only('userid', 'username', 'pass', 'check');
        // チェック
        $check = $input["check"];
        if(!$check == "A0293D"){
            return redirect('/');
        }

        // 登録
        $entryMember = new Member();
        $entryMember->user_id = $input["userid"];
        $entryMember->user_name = $input["username"];
        $entryMember->password = $input["pass"];
        $entryMember->save();
        return redirect('/member/index');
    }
    // ボランティアリスト削除
    public function memberDelete(Request $request){
        DB::beginTransaction();
        try{
            $input = $request->only('id');
            $id = $input['id'];
            $member = Member::find($id);
            $member->delete();
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect('/member/index');
    }

}
