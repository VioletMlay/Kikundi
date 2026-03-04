<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'repayment_id',
        'loan_id',
        'member_id',
        'amount',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Scopes
    public function scopeForLoan($query, $loanId)
    {
        return $query->where('loan_id', $loanId);
    }

    public function scopeForMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        // After creating a repayment, check if loan should be marked as completed
        static::created(function ($repayment) {
            $loan = $repayment->loan;
            if ($loan->remaining_balance <= 0 && $loan->status === 'Active') {
                $loan->status = 'Completed';
                $loan->save();
            }
        });
    }
}
