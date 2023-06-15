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
        Schema::create('student_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()
            ->constrained('payments')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('receipt_id')->nullable()
            ->constrained('receipt_students')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('processing_id')->nullable()
            ->constrained('processing_fees')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->foreignId('withdraw_id')->nullable()
            ->constrained('withdraws')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('type');
            $table->decimal('Debit')->nullable(); 
            $table->decimal('Credit')->nullable();
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
        Schema::dropIfExists('student_accounts');
    }
};
