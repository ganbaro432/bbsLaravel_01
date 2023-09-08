<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_counter', function (Blueprint $table) {
            $table->id();
            //$table->integer('thread_id');
            $table->foreignId('thread_id')->constrained('threads')->cascadeOnDelete();;
            $table->integer('counter')->default(0);
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_counter');
    }
};
