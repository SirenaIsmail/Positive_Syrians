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

        Schema::create('fund_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receipt_id')->nullable()
            ->constrained('receipt_students')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('withdraw_id')->nullable()
            ->constrained('withdraws')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->decimal('Debit')->nullable();
            $table->decimal('Credit')->nullable();
            $table->string('description')->nullable();
            $table->date('date');

            

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
        Schema::dropIfExists('fund_accounts');
    }
};
