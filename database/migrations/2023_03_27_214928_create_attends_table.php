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
        Schema::create('attends', function (Blueprint $table) {
            $table->id();
//            $table->foreignId('history_id')
//                ->constrained('histories')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
            $table->foreignId('card_id')
                ->constrained('cards')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('course_id')
                ->constrained('courses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->date('date_id');
            $table->boolean('state');
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
        Schema::dropIfExists('attends');
    }
};
