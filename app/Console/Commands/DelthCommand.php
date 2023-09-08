<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Thread;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class DelthCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'command:name';
    protected $signature = 'delth:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*
        $subSQL2 = Comment::select(['thread_id', DB::raw('max(created_at) as upd')])
        ->groupBy('thread_id');
        // 999以上のスレを表示2日前
        $fullthreads = Thread::select('threads.id')
        ->leftJoinSub($subSQL2, 'comments', 'threads.id', '=', 'comments.thread_id')
        ->leftJoin('comment_counter', 'threads.id', '=', 'comment_counter.thread_id')
        ->where('comment_counter.counter', '>', '999')
        ->where('comments.upd', '<=', date('Y/m/d', strtotime('-3 day', time())))
        ->get();   
        // 4週間以上前、レス数20以下
        $oldthreads = Thread::select('threads.id')
        ->leftJoinSub($subSQL2, 'comments', 'threads.id', '=', 'comments.thread_id')
        ->leftJoin('comment_counter', 'threads.id', '=', 'comment_counter.thread_id')
        ->where('comments.upd', '<=', date('Y/m/d', strtotime('-4 week', time())))
        ->where('comment_counter.counter', '<', '20')
        ->get();   
     
        // 999以上
        if($fullthreads->isEmpty()){

        } else {
            foreach($fullthreads as $item){
                // $this->line($item->id);
                DB::beginTransaction();
                try{
                    $thread = Thread::find($item->id);
                    $thread->delete();
                    DB::commit();
                }catch(\Exception $e){
                    DB::rollback();
                }
            }
        };

        // 4週間以上前
        if($oldthreads->isEmpty()){

        } else {
            foreach($oldthreads as $item){
                DB::beginTransaction();
                try{
                    $thread = Thread::find($item->id);
                    $thread->delete();
                    DB::commit();
                }catch(\Exception $e){
                    DB::rollback();
                }
            }
        };
        */

        return 0;
    }
}
