<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Dislike;
use App\Models\Comment;

class LikeController extends Controller
{
    //LIKE
    public function like(Request $request){
        
        $id = $request->review_id;
        $entryLike = new Like();

        // IPアドレスを取得し、個別に登録する
        $ip = $request->ip();
        // 既にあるかどうか、両テーブルから検索
        $existlike = Like::where('comment_id', $id)
        ->where('ip', $ip)
        ->first();
        $existdis = Dislike::where('comment_id', $id)
        ->where('ip', $ip)
        ->first();
        // カウントを登録,検証として違う場合も記録
        if(!$existlike && !$existdis){
            $entryLike->comment_id = $id;
            $entryLike->ip = $ip;
            $entryLike->save();
            $likecount = Comment::withCount('like')->find($id)->like_count;
            $param =[
                'review_likes_count' => $likecount,
            ];
            // commentsテーブルのlike_sumに合計を登録
            $likesql = Comment::select('id')
            ->withCount('like')
            ->where('id', $id)
            ->first();
            $dislikesql = Comment::select('id')
            ->withCount('dislike')
            ->where('id', $id)
            ->first();
            // likeカウントがあるかどうか
            if(!isset($likesql->like_count)){
                $like = 0;
            }else{
                $like = $likesql->like_count;
            };
            if(!isset($dislikesql->dislike_count)){
                $dislike = 0;
            }else{
                $dislike = $dislikesql->dislike_count;
            }
            $like_sum = $like - $dislike;
            $entryComment = Comment::find($id);
            $entryComment->like_sum = $like_sum;
            $entryComment->save();

            return response()->json($param);            
        }  
        return;
    }

    // DISLIKE
    public function dislike(Request $request){
        // reviewidがわかりづらい、コメントのidのこと
        $id = $request->review_id;
        $entryDislike = new Dislike();

        // IPアドレスを取得し、個別に登録する
        $ip = $request->ip();
        // 既にあるかどうか、両テーブルから検索
        $existlike = Like::where('comment_id', $id)
        ->where('ip', $ip)
        ->first();
        $existdis = Dislike::where('comment_id', $id)
        ->where('ip', $ip)
        ->first();
        // カウントを登録,検証として違う場合も記録
        if(!$existlike && !$existdis){
            $entryDislike->comment_id = $id;
            $entryDislike->ip = $ip;
            $entryDislike->save();

            // カウントをかえす
            $dislikecount = Comment::withCount('dislike')->find($id)->dislike_count;
            $param =[
                'review_dislikes_count' => $dislikecount,
            ];

            // commentsテーブルのlike_sumに合計を登録
            $likesql = Comment::select('id')
            ->withCount('like')
            ->where('id', $id)
            ->first();
            $dislikesql = Comment::select('id')
            ->withCount('dislike')
            ->where('id', $id)
            ->first();
            // likeカウントがあるかどうか
            if(!isset($likesql->like_count)){
                $like = 0;
            }else{
                $like = $likesql->like_count;
            };
            if(!isset($dislikesql->dislike_count)){
                $dislike = 0;
            }else{
                $dislike = $dislikesql->dislike_count;
            }
            $like_sum = $like - $dislike;
            $entryComment = Comment::find($id);
            $entryComment->like_sum = $like_sum;
            $entryComment->save();
                        
            return response()->json($param);            
        } 
        return ;
    }   

}
