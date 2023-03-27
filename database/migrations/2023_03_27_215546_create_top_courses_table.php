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
        Schema::create('top_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscribe_id')
                ->constrained('subscribes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('date_id')
                ->constrained('dates')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('branch_id')
                ->constrained('branches')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('top_courses');
    }
};
