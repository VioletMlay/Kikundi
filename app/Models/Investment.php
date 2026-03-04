<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'member_id',
        'amount',
        'investment_date',
        'quarter',
        'year',
        'notes',
    ];

    protected $casts = [
        'investment_date' => 'date',
        'amount' => 'decimal:2',
        'year' => 'integer',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Scopes
    public function scopeForQuarter($query, $year, $quarter)
    {
        return $query->where('year', $year)
                     ->where('quarter', $quarter);
    }

    public function scopeForYear($query, $year)
    {
        return $query->where('year', $year);
    }

    public function scopeForPeriod($query, $year, $startQuarter, $endQuarter)
    {
        $quarters = [];
        for ($i = $startQuarter; $i <= $endQuarter; $i++) {
            $quarters[] = 'Q' . $i;
        }
        
        return $query->where('year', $year)
                     ->whereIn('quarter', $quarters);
    }

    // Helper method to determine quarter from date
    public static function getQuarterFromDate($date)
    {
        $month = $date->month;
        return 'Q' . ceil($month / 3);
    }

    // Boot method to auto-calculate quarter if not provided
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($investment) {
            if (!$investment->quarter) {
                $investment->quarter = self::getQuarterFromDate($investment->investment_date);
            }
            if (!$investment->year) {
                $investment->year = $investment->investment_date->year;
            }
        });
    }
}
