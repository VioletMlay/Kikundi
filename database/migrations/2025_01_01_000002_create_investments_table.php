<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->date('investment_date');
            $table->enum('quarter', ['Q1', 'Q2', 'Q3', 'Q4']);
            $table->year('year');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['member_id', 'year', 'quarter']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
