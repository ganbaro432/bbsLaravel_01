<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SimpleLoginController;
use App\Http\Controllers\SimpleLogoutController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ArticleController;
use App\Models\Member;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*一覧*/
Route::get('/', [ThreadController::class, 'index']);
/*ソート*/
Route::get('/sort/{sort}', [ThreadController::class, 'sort']);
/*利用規約や説明ページへの移動*/
Route::view('/agreement', 'otherpage.agreement');
Route::view('/contact', 'otherpage.contact');
Route::view('/recruit', 'otherpage.recruit');
// 記事QA
Route::view('/help', 'otherpage.articleQA');

// 検索機能スレッド
Route::get('/search', [ThreadController::class, 'search'])->name('search.show');
// カテゴリ検索機能スレッド
Route::get('/category/{categorynm}', [ThreadController::class, 'categorysearch'])->name('category.show');
// コメントID検索
Route::get('/user/{id}', [CommentController::class, 'idsearch'])->name('comment.idsearch');

/*スレッド登録画面に移動*/
Route::get('/thread/post', function(){
    return view('thread_form');
});
/*スレッド登録*/
Route::post('/create', [ThreadController::class, 'create'])
->middleware('throttle:2, 120');
/*スレッド詳細*/
Route::get('/thread/{id}/{sort?}', [ThreadController::class, 'show'])->name('thread.show');

/*コメントコントロールでコメント登録 */
Route::post('/res', [CommentController::class, 'create']);
/*返信コメント登録*/
Route::post('rep', [CommentController::class, 'rep']);
// コメント送信確認画面
Route::get('/confirmres', [CommentController::class, 'confirmres']);
// 返信コメント送信確認画面
Route::get('/confirmrep', [CommentController::class, 'confirmrep']);
/*コメント詳細画面(廃止)*/
Route::get('/thread/{tid}/comment/{cid}', [CommentController::class, 'show'])->name('comment.show');
// 返信コメントをjQueryで取得
Route::get('/repshow', [CommentController::class, 'repshow']);
// 返信先コメントをポップアップで表示
Route::get('/reppop', [CommentController::class, 'reppop']);


// 
// ボランティア用
Route::view('/member', 'member.memberform');
// メンバーベースファイル確認用
Route::view('/member/sample', 'member.m_app');
// 
/*ボランティア削除人のログインページ*/
Route::get('/member/form', function(){
    return view('member.memberform');
});
//ログイン処理
Route::post('/vlogin', [SimpleLoginController::class, 'vlogin']);
//ログアウト処理
Route::post('/vlogout', [SimpleLogoutController::class, 'vlogout']);
/*ボランティア削除人のログイン後*/
Route::get('/member/index', [MemberController::class, 'index'])->middleware("simple_auth");
/*ソート*/
Route::get('/member/index/{sort}', [MemberController::class, 'sort'])->middleware("simple_auth");
/*詳細*/
Route::get('/member/index/thread/{id}/{sort?}', [MemberController::class, 'show'])->name('member.show')->middleware("simple_auth");;
//コメント削除文にアップデート
Route::post('updel', [MemberController::class, 'update']);
// ボランティア検索機能スレッド
Route::get('/member/search', [MemberController::class, 'search'])->name('membersearch.show');
/*スレッド削除確認ページへ */
Route::get('/member/index/delth/{id}/', [MemberController::class, 'thdelconfirm'])->name('member.thdel')->middleware("simple_auth");
// スレッド削除
Route::post('thdel', [MemberController::class, 'thdel']);

/*管理用コメント詳細画面*/
Route::get('/member/comment/{id}/', [MemberController::class, 'repshow'])->name('mcomment.show')->middleware("simple_auth");
/*管理人返信コメント登録*/
Route::post('kanrirep', [MemberController::class, 'rep'])
->middleware("simple_auth");
// 管理人用画像一覧ページ
Route::get('/member/article/image', [MemberController::class, 'articleImage']);
// 管理人用画像削除
Route::get('/member/article/image/delete', [MemberController::class, 'articleImageDelete'])->name('member.articleimgdelete');
// 管理人用ボランティアリスト
Route::get('/member/list', [MemberController::class, 'memberList'])
->middleware("simple_auth");
// 管理人用ボランティア追加
Route::post('memberregist', [MemberController::class, 'memberRegist']);
// 管理人用ボランティア削除
Route::post('memberdel', [MemberController::class, 'memberDelete']);

// 
// 管理用記事一覧画面
Route::get('/member/article', [MemberController::class, 'article'])->middleware("simple_auth");
// 記事公開、非公開処理
Route::post('articlepublic', [MemberController::class, 'public'])->middleware("simple_auth");
// 管理人用記事編集画面、削除ボタンもつける
Route::post('/member/article/edit/{id}', [MemberController::class, 'edit'])->name('member.articleEdit')->middleware("simple_auth");
// 管理人用記事アップデート処理
Route::post('updatearticle', [MemberController::class, 'updateArticle'])->name('article.update');
// 管理人用記事削除処理
Route::get('/member/articledelete/{id}', [MemberController::class, 'deleteArticle'])->name('article.delete');

/*LIKEボタン */
Route::post('/like', [LikeController::class, 'like'])
->middleware('throttle:20, 1');
/*DISLIKEボタン */
Route::post('/dislike', [LikeController::class, 'dislike'])
->middleware('throttle:5, 30');

// 記事作成
// 記事作成画面
Route::view('/article', 'article.article_form')->name('article.form');
// 記事投稿
Route::post('/article', [ArticleController::class, 'post'])->name('article.post');
// summernoteエディタ画像システム、10分に10枚
Route::get('/article/upload', [ArticleController::class, 'image'])->name('article.image')->middleware('throttle:10, 10');
Route::post('/article/upload', [ArticleController::class, 'image'])->name('article.image')->middleware('throttle:10, 10');
// 投稿プレビュー画面へ
Route::post('/article/preview', [ArticleController::class, 'preview'])->name('article.preview');
// 投稿完了後画面
Route::get('/article/thanks', [ArticleController::class, 'complete'])->name('article.complete');
// 記事一覧画面
Route::get('/article/index', [ArticleController::class, 'index'])->name('article.index');
// 記事編集画面へのパスワード入力ページへ
Route::get('/article/content/check/{id}', [ArticleController::class, 'checkpage'])->name('article.checkpage');
// パスワード確認し、編集画面へ
Route::post('/article/content/edit', [ArticleController::class, 'ariclePassCheck'])->name('article.passcheck');
// 記事詳細画面
Route::get('/article/content/{id}', [ArticleController::class, 'show'])->name('article.show');
// 記事アップデート処理
Route::post('articleupdate', [ArticleController::class, 'update'])->name('article.update');



/*
Route::get('/', function () {
    return view('welcome');
});
*/