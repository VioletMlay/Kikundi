<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_id')->unique();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->decimal('loan_amount', 12, 2);
            $table->decimal('upfront_fee', 12, 2);
            $table->decimal('net_disbursed', 12, 2);
            $table->decimal('total_to_repay', 12, 2);
            $table->date('loan_date');
            $table->date('due_date');
            $table->enum('status', ['Active', 'Completed', 'Defaulted'])->default('Active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['member_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
