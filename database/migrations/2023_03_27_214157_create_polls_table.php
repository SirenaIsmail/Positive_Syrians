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
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('mother_name');
            $table->string('address');
            $table->foreignId('branch_id')
            ->constrained('branches')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('first_subj')
                ->constrained('subjects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('secound_subj')
                ->constrained('subjects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('third_subj')
                ->constrained('subjects')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('first_time');
            $table->string('secound_time');
            $table->string('third_time');
            $table->date('poll_date');
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
        Schema::dropIfExists('polls');
    }
};
