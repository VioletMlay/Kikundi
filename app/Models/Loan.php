<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'loan_id',
        'member_id',
        'loan_amount',
        'upfront_fee',
        'net_disbursed',
        'total_to_repay',
        'loan_date',
        'due_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'due_date' => 'date',
        'loan_amount' => 'decimal:2',
        'upfront_fee' => 'decimal:2',
        'net_disbursed' => 'decimal:2',
        'total_to_repay' => 'decimal:2',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    // Computed Attributes
    public function getTotalRepaidAttribute()
    {
        return $this->repayments()->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total_to_repay - $this->total_repaid;
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'Active' && $this->due_date->isPast();
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) {
            return 0;
        }
        return $this->due_date->diffInDays(now());
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function scopeDefaulted($query)
    {
        return $query->where('status', 'Defaulted');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'Active')
            ->where('due_date', '<', now());
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('loan_date', [$startDate, $endDate]);
    }

    // Boot method for automatic calculations
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($loan) {
            $feePercentage = Setting::where('key', 'upfront_fee_percentage')->first()->value ?? 0.10;
            $loanPeriodMonths = (int) (Setting::where('key', 'loan_period_months')->first()->value ?? 3);

            // Calculate upfront fee (10%)
            $loan->upfront_fee = $loan->loan_amount * $feePercentage;

            // Calculate net disbursed (loan amount - fee)
            $loan->net_disbursed = $loan->loan_amount - $loan->upfront_fee;

            // Total to repay is the full loan amount
            $loan->total_to_repay = $loan->loan_amount;

            // Calculate due date (3 months from loan date)
            if (!$loan->due_date) {
                $loan->due_date = Carbon::parse($loan->loan_date)->addMonths($loanPeriodMonths);
            }
        });

        // Automatically mark loan as completed when fully repaid
        static::updating(function ($loan) {
            if ($loan->remaining_balance <= 0 && $loan->status === 'Active') {
                $loan->status = 'Completed';
            }
        });
    }

    // Helper method to check if loan can be fully repaid
    public function canRepay($amount)
    {
        return $amount <= $this->remaining_balance;
    }
}
