<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('note_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedInteger('note_id');
            $table->unique(['note_id', 'locale']);
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');
            $table->string('title', 255)->index();
            $table->string('text', 500)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_translations');
    }
}
