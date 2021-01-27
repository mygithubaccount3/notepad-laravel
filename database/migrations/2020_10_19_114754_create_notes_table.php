<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {

        Schema::create('notes', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->uuid('uid');
            $table->integer('user_id')->unsigned();
            $table->index('user_id');
            $table->string('category', 100)->default('general')->index();
            $table->string('visibility', 10)->default('public')->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
}
