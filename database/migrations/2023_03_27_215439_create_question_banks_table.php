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
        Schema::create('question_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('type');   //0 traditional  or  1 automated
            $table->text('question');
            $table->text('A')->nullable();
            $table->text('B')->nullable();
            $table->text('C')->nullable();
            $table->text('D')->nullable();
            $table->integer('check')->nullable(); //1checked
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
        Schema::dropIfExists('question_banks');
    }
};
