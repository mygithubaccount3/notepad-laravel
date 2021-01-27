<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSharedNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shared_notes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id', false, true);
            $table->integer('note_id', false, true);
            $table->index('user_id');
            $table->index('note_id');
            $table->enum('state', [
                'pending', 'accepted'
            ])->default('pending');

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('note_id')->references('id')->on('notes')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shared_notes');
    }
}
