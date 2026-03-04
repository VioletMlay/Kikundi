<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'member_id',
        'full_name',
        'phone_number',
        'member_type',
        'date_joined',
        'status',
        'notes',
    ];

    protected $casts = [
        'date_joined' => 'date',
    ];

    // Relationships
    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    // Business Logic
    public function getTotalInvestmentAttribute()
    {
        return $this->investments()->sum('amount');
    }

    public function getMaxLoanEligibleAttribute()
    {
        $loanMultiplier = Setting::where('key', 'loan_multiplier')->first()->value ?? 2;
        return $this->total_investment * $loanMultiplier;
    }

    public function getActiveLoansAttribute()
    {
        return $this->loans()->where('status', 'Active')->get();
    }

    public function getTotalOutstandingAttribute()
    {
        return $this->active_loans->sum(function ($loan) {
            return $loan->remaining_balance;
        });
    }

    public function getHasActiveLoanAttribute()
    {
        return $this->loans()->where('status', 'Active')->exists();
    }

    public function canBorrow($amount)
    {
        // Cannot borrow if there is an active loan
        if ($this->has_active_loan) {
            return false;
        }

        $maxEligible = $this->max_loan_eligible;
        $currentOutstanding = $this->total_outstanding;

        return ($currentOutstanding + $amount) <= $maxEligible;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'Inactive');
    }

    public function scopeStudents($query)
    {
        return $query->where('member_type', 'Student');
    }

    public function scopeWorkers($query)
    {
        return $query->where('member_type', 'Worker');
    }
}
