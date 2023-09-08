<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Articleimage;
use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Comment;
use App\Models\Commentcounter;
use App\Models\Category;

use Illuminate\Support\Facades\DB;

class ThreadController extends Controller
{

    // 共通関数
    // 
    // カテゴリ
    function categoryList(){
        $category = Category::whereIn('id',[1,2,3,4,5,6,7,8])
        ->get();
        return $category;
    }

    // 投稿記事をトップページ用に取得
    function articleList(){
        $subSQL = Articleimage::select(['article_id' , DB::raw('min(path) as path')])
        ->groupBy('article_id');
        $articles = Article::select('articles.id', 'articles.created_at', 'articles.title', 'articles.category_id', 'articleimages.path', 'categories.categorynm')
        ->leftJoinSub($subSQL, 'articleimages', 'articles.id', '=', 'articleimages.article_id')
        ->leftJoin('categories', 'articles.category_id', '=', 'categories.id')        
        ->where('articles.open_flg', '=', 1)
        ->orderBy('created_at', 'desc')
        ->limit(2)
        ->get();  
        return $articles;
    }

    //トップページの表示
    function index(){
        

        // 最新コメントの取得
        $newcomments = Comment::with('thread')->where([['kanriflag', '!=', 2]])->orderBy('created_at', 'desc')->limit(5)->get();

        $subSQL = Comment::select(['thread_id', DB::raw('max(created_at) as upd')])
        ->groupBy('thread_id');

        $threads = Thread::select('threads.id', 'threads.title', 'threads.created_at', 'categories.categorynm')
        ->leftJoinSub($subSQL, 'comments', 'comments.thread_id', 'threads.id')
        ->leftJoin('categories', 'threads.category_id', '=', 'categories.id')
        // ->where('comments.upd', '>=', date('Y/m/d', strtotime('-3 week', time())))
        ->orderBy('upd', 'desc')
        ->paginate(50);
        $counter = CommentCounter::paginate(50);
        // カテゴリ取得
        $category = $this->categoryList();
        
        // article記事取得
        $articles = $this->articleList();
        
        return view("thread_list", compact('threads','counter', 'newcomments', 'category', 'articles'));

    }

    //トップページのソート
    function sort($sort){
        // 最新コメントの取得
        $newcomments = Comment::with('thread')->orderBy('created_at', 'desc')->limit(5)->get();
        // article記事取得
        $articles = $this->articleList();
        // カテゴリ
        $category = $this->categoryList();

        $flag = $sort;
        $order = 'asc';
        if($flag == 'new'){
            $flag = 'created_at';
            $order = 'desc';
            $subSQL = Comment::select(['thread_id', DB::raw('max(created_at) as upd')])
            ->groupBy('thread_id');

            $threads = Thread::select('threads.id', 'threads.title', 'threads.created_at', 'categories.categorynm')
            ->leftJoinSub($subSQL, 'comments', 'comments.thread_id', 'threads.id')
            ->leftJoin('categories', 'threads.category_id', '=', 'categories.id')
            // ->where('comments.upd', '>=', date('Y/m/d', strtotime('-3 week', time())))
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            $counter = CommentCounter::paginate(50);
             
            return view("thread_list", compact('threads','counter', 'newcomments', 'category', 'articles'));

        };

        if($flag == 'popular'){
            $flag = 'comment_counter.counter';
            $order = 'desc';

            // updは最終更新日から3日以内のを取ってくる意味、createdでもいいかも
            $subSQL = Comment::select(['thread_id', DB::raw('max(created_at) as upd')])
            ->groupBy('thread_id');
            $threads = Thread::select('threads.id', 'threads.title', 'threads.created_at', 'comment_counter.counter',   'categories.categorynm')
            ->leftJoin('comment_counter', 'threads.id', '=', 'comment_counter.thread_id')
            ->leftJoin('categories', 'threads.category_id', '=', 'categories.id')
            ->leftJoinSub($subSQL, 'comments', 'threads.id', '=', 'comments.thread_id')
            ->where('comments.upd', '>=', date('Y/m/d H:i:s', strtotime('-6 day')))
            ->orderBy('comment_counter.counter', 'desc')
            ->paginate(50);
            

            return view("thread_list", compact('threads', 'newcomments', 'category', 'articles'));
        }

        if($flag == 'update'){

            $subSQL = Comment::select(['thread_id', DB::raw('max(created_at) as upd')])
            ->groupBy('thread_id');
            $threads = Thread::select('threads.id', 'threads.title', 'threads.created_at', 'categories.categorynm')
            ->leftJoinSub($subSQL, 'comments', 'comments.thread_id', 'threads.id')
            ->leftJoin('categories', 'threads.category_id', '=', 'categories.id')
            // ->where('comments.upd', '>=', date('Y/m/d', strtotime('-3 week', time())))
            ->orderBy('upd', 'desc')
            ->paginate(50);
            $counter = CommentCounter::paginate(50);
            
    
            return view("thread_list", compact('threads','counter', 'newcomments', 'category', 'articles'));
        }

    }

    // 検索機能
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
        // カテゴリ取得
        $category = $this->categoryList();
        return view("search", compact('posts', 'keyword', 'newcomments', 'category'));
    }
    
    //カテゴリ検索
    function categorysearch($categorynm){
        $threads = Thread::select('threads.*', 'comment_counter.counter',   'categories.categorynm')
        ->leftJoin('comment_counter', 'threads.id', '=', 'comment_counter.thread_id')
        ->leftJoin('categories', 'threads.category_id', '=', 'categories.id')
        ->where('categories.categorynm','=', $categorynm)
        ->paginate(50);
        // 最新コメントの取得
        $newcomments = Comment::with('thread')->orderBy('created_at', 'desc')->limit(5)->get();
        // カテゴリ取得
        $category = $this->categoryList();
 
        return view("otherpage.category", compact('threads', 'newcomments', 'categorynm', 'category'));
    }

    //新規スレッド作成
    function create(Request $request){

        $request->validate([
            'name' => 'required|string|max:20',
            'title' => 'required|string|max:100',
            'body' => 'required|string|min:5|max:800',
            // 'g-recaptcha-response' => 'required|recaptchav3:register,0.5'
         ],
        [
            'name.required' =>'名前を入力してください',
            'name.max' =>'20文字以内で名前を入力してください',
            'title.required' =>'タイトルを入力してください',
            'title.max' =>'100文字以下でタイトルを入力してください',
            'body.required' =>'内容を入力してください',
            'body.max' =>'800文字以内で内容を入力してください',
            'body.min' =>'5文字以上で内容を入力してください',
        ]);
        //トランザクション
        DB::beginTransaction();
        try{
        
        $input = $request->only('name', 'title', 'body', 'category');

        //先にインスタンス生成
        $entryThread = new Thread();
        $entryPost = new Comment();
        $entryCounter = new Commentcounter();
        //スレッドテーブルにタイトルのみ保存
        $entryThread->title = $input["title"];
        $entryThread->category_id = $input["category"];
        $entryThread->save();
        //コメントカウントに保存
        $counter = 1;
        
        $entryCounter->thread_id = $entryThread->id;
        $entryCounter->counter = $counter;
        $entryCounter->save();
        //リレーションテーブル
        $entryPost->name = $input["name"];
        //コメントURL化メソッドをためす
        $entryPost->body = $entryPost->replaceUrl($input['body']);
        $entryPost->user_id = $entryPost->idcreat($request);
        //さらにリレーション先から取得
        $entryPost->thread_id = $entryThread->id;
        //コメントカウンターに1を挿入
        $entryPost->comment_serial = $counter;
        $entryPost->save();
        
        DB::commit();
        } catch(\Exception $e){
            DB::rollback();
        }
        
        // 二重投稿防止にトークン再生成
        $request->session()->regenerateToken();
        return redirect('/');

    }

    //コメント一覧
    function show($id, $sort = null){
        //join一発で
        $header = Thread::find($id);
        $category = Thread::leftJoin('categories', 'threads.category_id', '=', 'categories.id')
        ->where('threads.id', $id)
        ->first();
        $flag = $sort;

        // 最新コメントの取得
        $newcomments = Comment::with('thread')->where([['kanriflag', '!=', 2]])->orderBy('created_at', 'desc')->limit(5)->get();
        
        if(!isset($flag)){

            // $subSQL = Comment::select(['rep_id', DB::raw('count(*) as counter')])
            // ->groupBy('rep_id');     

            // // selectで指定しないともってこない
            // $comments = Comment::select('comments.*', 'cnt.counter')
            // ->leftJoinSub($subSQL, 'cnt', 'comments.id', 'cnt.rep_id')
            // ->withCount('dislike')
            // ->withCount('like')
            // ->where([['thread_id', $id]])
            // ->paginate(100);
            // // ->toSql();
            // // dd($comments);
            $subSQL = Comment::select(['rep_id', DB::raw('count(*) as counter')])
            ->where([['kanriflag', '!=', 2]])
            ->groupBy('rep_id');     

            $comments = Comment::select('comments.id', 'comments.user_id', 'comments.rep_id', 'comments.thread_id', 'comments.name', 'comments.body', 'comments.comment_serial', 'comments.created_at', 'comments.kanriflag', 'comments.like_sum', 'cnt.counter')
            ->leftJoinSub($subSQL, 'cnt', 'comments.id', 'cnt.rep_id')
            ->withCount('dislike')
            ->withCount('like')
            ->where([['thread_id', $id]])
            ->where([['kanriflag', '!=', 2]])
            ->orderBy('created_at', 'desc')
            ->paginate(100);            

        } elseif($flag == 'new'){

            $subSQL = Comment::select(['rep_id', DB::raw('count(*) as counter')])
            ->where([['kanriflag', '!=', 2]])
            ->groupBy('rep_id');     

            $comments = Comment::select('comments.id', 'comments.user_id', 'comments.rep_id', 'comments.thread_id', 'comments.name', 'comments.body', 'comments.comment_serial', 'comments.created_at', 'comments.kanriflag', 'comments.like_sum', 'cnt.counter')
            ->leftJoinSub($subSQL, 'cnt', 'comments.id', 'cnt.rep_id')
            ->withCount('dislike')
            ->withCount('like')
            ->where([['thread_id', $id]])
            ->where([['kanriflag', '!=', 2]])
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        }else{
 
            $subSQL = Comment::select(['rep_id', DB::raw('count(*) as counter')])
            ->where([['kanriflag', '!=', 2]])
            ->groupBy('rep_id');     

            $comments = Comment::select('comments.id', 'comments.user_id', 'comments.rep_id', 'comments.thread_id', 'comments.name', 'comments.body', 'comments.comment_serial', 'comments.created_at', 'comments.kanriflag', 'comments.like_sum', 'cnt.counter')
            ->leftJoinSub($subSQL, 'cnt', 'comments.id', 'cnt.rep_id')
            ->withCount('dislike')
            ->withCount('like')
            ->where([['thread_id', $id]])
            ->where([['kanriflag', '!=', 2]])
            ->orderBy('created_at', 'asc')
            ->paginate(100);   
            
        };

        // 実験
       

        // カテゴリ取得
        $ctlist = $this->categoryList(); 
        return view('thread', compact('header', 'comments', 'newcomments', 'category', 'ctlist'));
        
    }


}
