<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, decimal, boolean
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'loan_multiplier',
                'value' => '2',
                'type' => 'integer',
                'description' => 'Members can borrow up to this multiplier times their investment',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'upfront_fee_percentage',
                'value' => '0.10',
                'type' => 'decimal',
                'description' => 'Percentage deducted from loan amount as upfront fee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'loan_period_months',
                'value' => '3',
                'type' => 'integer',
                'description' => 'Standard loan repayment period in months',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'min_investment_student',
                'value' => '5000',
                'type' => 'decimal',
                'description' => 'Minimum investment for student members',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'min_investment_worker',
                'value' => '50000',
                'type' => 'decimal',
                'description' => 'Minimum investment for worker members',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'kikundi_name',
                'value' => 'Kikundi cha Wanandugu',
                'type' => 'string',
                'description' => 'Name of the kikundi group',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
