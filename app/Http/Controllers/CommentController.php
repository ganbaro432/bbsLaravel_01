<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Thread;
use App\Models\Comment;
use App\Models\Commentcounter;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CommentController extends Controller
{
    //コメント登録   
    function create(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:20',
                'body' => 'required|string|max:800',
                // 'g-recaptcha-response' => 'required|recaptchav3:register,0.5',
            ],
            [
                'name.required' => '名前を入力してください',
                'name.max' => '20文字以内で名前を入力してください',
                'body.required' => '内容を入力してください',
                'body.max' => '800文字以内で内容を入力してください',
                // 'g-recaptcha-response' => 'BOT判定、間違いの場合は連絡を',
            ]
        );

        DB::beginTransaction();
        //トランザクション
        try {
            $input = $request->only('user_id', 'thread_id', 'name', 'body');

            //コメントテーブルに保存
            $entryComment = new Comment();
            $entryComment->user_id = $entryComment->idcreat($request);
            //$entryComment->user_id = $input['user_id'];
            $entryComment->ip = $request->ip();
            $entryComment->thread_id = $input['thread_id'];
            $entryComment->name = $input['name'];
            //コメントURL化メソッドをためす
            // $entryComment->body = $entryComment->replaceUrl($input['body']);
            $entryComment->body = $entryComment->replaceUrlfull($input['body']);

            //共通化select
            $select = Commentcounter::where('thread_id', $request->thread_id);
            $i = $select->first('counter')->counter;
            $entryComment->comment_serial = $i + 1;
            $entryComment->save();

            //カウンターテーブルをアップデート
            $updateCounter = new CommentCounter();
            //共通化select
            $updateCounter->counter = $select->increment('counter');
            //アップデート処理は要検討
            $updateCounter->update();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        // 二重投稿防止にトークン再生成
        $request->session()->regenerateToken();
        // return back()->with('message', 'コメントを投稿しました');
        return redirect()->route('thread.show', ['id' => $request->thread_id])->with('message', 'コメントを投稿しました');
    }

    //返信コメント登録 コメント番号就く前のスレッドはエラーがでる
    function rep(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:20',
                'body' => 'required|string|max:800',
                // 'g-recaptcha-response' => 'required|recaptchav3:register,0.5',
            ],
            [
                'name.required' => '名前を入力してください',
                'name.max' => '20文字以内で名前を入力してください',
                'body.required' => '内容を入力してください',
                'body.max' => '800文字以内で内容を入力してください',
            ]
        );

        DB::beginTransaction();
        try {


            $input = $request->only('thread_id', 'name', 'body', 'rep_id');

            $entryRep = new Comment();
            $entryRep->user_id = $entryRep->idcreat($request);
            $entryRep->ip = $request->ip();
            $entryRep->thread_id = $input['thread_id'];
            $entryRep->name = $input['name'];
            //コメントURL化メソッドをためす
            // $entryRep->body = $entryRep->replaceUrl($input['body']);
            $entryRep->body = $entryRep->replaceUrlfull($input['body']);

            $entryRep->rep_id = $input['rep_id'];

            $select = Commentcounter::where('thread_id', $request->thread_id);
            $i = $select->first('counter')->counter;
            $entryRep->comment_serial = $i + 1;
            $entryRep->save();

            $updateCounter = new CommentCounter();
            $updateCounter->counter = $select->increment('counter');
            $updateCounter->update();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        // return back()->with('message', '返信を投稿しました');
        return redirect()->route('thread.show', ['id' => $request->thread_id])->with('message', 'コメントを投稿しました');
    }

    // コメント確認画面
    function confirmres(Request $request)
    {

        // 20230813_ngwordチェック
        $entryComment = new Comment();
        $check = $entryComment->ngwordCheck($request['body']);
        if (!$check) {
            return back();
        } 

        $hash = array(
            'subtitle' => '確認画面',
            'request' => $request,
        );
        return view('otherpage.confirmres')->with($hash);
    }
    // 返信確認画面
    function confirmrep(Request $request)
    {
        // 20230813_ngwordチェック
        $entryComment = new Comment();
        $check = $entryComment->ngwordCheck($request['body']);
        if (!$check) {
            return back();
        } 


        $hash = array(
            'subtitle' => '確認画面',
            'request' => $request,
        );
        return view('otherpage.confirmrep')->with($hash);
    }

    //コメント詳細表示 もう使っていない
    function show($tid, $cid)
    {
        //1件だけならfind
        $header = Comment::withCount('dislike')
            ->withCount('like')
            ->find($cid);
        $comments = Comment::withCount('dislike')
            ->withCount('like')
            ->where('rep_id', $cid)
            ->paginate(200);
        $allcom = Comment::where('thread_id', $tid)->get();

        return view('comment', compact('header', 'comments', 'allcom'));
    }

    //投稿者ID検索
    function idsearch($id)
    {
        $threads = Comment::select('comments.*', 'threads.title as thtitle', 'threads.id as thid')
            ->leftJoin('threads', 'comments.thread_id', '=', 'threads.id')
            ->where('comments.user_id', $id)
            ->paginate(50);

        // カテゴリ取得
        $category = Category::whereIn('id', [1, 2, 3, 4, 5, 6, 7, 8])
            ->get();
        $newcomments = Comment::with('thread')->orderBy('created_at', 'desc')->limit(5)->get();
        return view('otherpage.commentid', compact('threads', 'category', 'newcomments', 'id'));
    }

    // 返信コメントをjQueryで取得
    function repshow(request $request)
    {
        $id = $request->reptarget;
        $replist = Comment::withCount('dislike')
            ->withCount('like')
            ->where('rep_id', $id)
            ->where('kanriflag', '<>', '2')
            ->get();

        return response()->json($replist);
    }

    // 返信をポップアップで表示
    function reppop(request $request)
    {
        // $id = $request->id;

        $serial = $request->serial;
        $threadid = $request->threadid;
        // $repcomment = Comment::select('reply.user_id', 'reply.name', 'reply.body', 'reply.comment_serial', 'reply.created_at')
        // ->leftJoin('comments as reply', 'comments.rep_id', '=', 'reply.id')
        // ->where('comments.id', $id)
        // ->first();
        $repcomment = Comment::select('user_id', 'name', 'body', 'comment_serial', 'created_at')
            ->where('thread_id', $threadid)
            ->where('comment_serial', $serial)
            ->where('kanriflag', '<>', '2')
            ->first();

        return response()->json($repcomment);
    }
}
