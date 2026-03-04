<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Loan;
use App\Models\Repayment;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function quarterly(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $quarter = $request->get('quarter', 'Q1');

        $quarterDates = $this->getQuarterDates($year, $quarter);

        $data = $this->getReportData($quarterDates['start'], $quarterDates['end']);

        return response()->json([
            'success' => true,
            'report_type' => 'Quarterly',
            'period' => "{$quarter} {$year}",
            'start_date' => $quarterDates['start'],
            'end_date' => $quarterDates['end'],
            'data' => $data
        ]);
    }

    public function biannual(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $period = $request->get('period', 'H1'); // H1 or H2

        $dates = $this->getBiannualDates($year, $period);

        $data = $this->getReportData($dates['start'], $dates['end']);

        return response()->json([
            'success' => true,
            'report_type' => 'Bi-Annual',
            'period' => "{$period} {$year}",
            'start_date' => $dates['start'],
            'end_date' => $dates['end'],
            'data' => $data
        ]);
    }

    public function annual(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $startDate = Carbon::create($year, 1, 1)->startOfDay();
        $endDate = Carbon::create($year, 12, 31)->endOfDay();

        $data = $this->getReportData($startDate, $endDate);

        // Add quarterly breakdown for annual report
        $quarterlyBreakdown = [];
        foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
            $quarterDates = $this->getQuarterDates($year, $quarter);
            $quarterData = $this->getReportData($quarterDates['start'], $quarterDates['end']);
            $quarterlyBreakdown[$quarter] = $quarterData;
        }

        return response()->json([
            'success' => true,
            'report_type' => 'Annual',
            'period' => $year,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $data,
            'quarterly_breakdown' => $quarterlyBreakdown
        ]);
    }

    public function dashboard()
    {
        $totalInvestments = Investment::sum('amount');
        $totalLoans = Loan::sum('loan_amount');
        $totalFeesCollected = Loan::sum('upfront_fee');
        $totalDisbursed = Loan::sum('net_disbursed');
        $totalRepayments = Repayment::sum('amount');
        $totalToBeRepaid = Loan::where('status', 'Active')->sum('total_to_repay');
        $totalRepaidActive = Repayment::whereHas('loan', function ($query) {
            $query->where('status', 'Active');
        })->sum('amount');

        $outstandingBalance = $totalToBeRepaid - $totalRepaidActive;
        $availableFunds = $totalInvestments + $totalFeesCollected + $totalRepayments - $totalDisbursed;

        $stats = [
            'members' => [
                'total' => Member::count(),
                'active' => Member::active()->count(),
                'inactive' => Member::inactive()->count(),
                'students' => Member::students()->count(),
                'workers' => Member::workers()->count(),
            ],
            'investments' => [
                'total_amount' => $totalInvestments,
                'count' => Investment::count(),
                'by_type' => [
                    'student' => Investment::whereHas('member', function ($q) {
                        $q->where('member_type', 'Student');
                    })->sum('amount'),
                    'worker' => Investment::whereHas('member', function ($q) {
                        $q->where('member_type', 'Worker');
                    })->sum('amount'),
                ]
            ],
            'loans' => [
                'total_loans' => Loan::count(),
                'active_loans' => Loan::active()->count(),
                'completed_loans' => Loan::completed()->count(),
                'defaulted_loans' => Loan::defaulted()->count(),
                'overdue_loans' => Loan::overdue()->count(),
                'total_disbursed' => $totalDisbursed,
                'total_fees_collected' => $totalFeesCollected,
                'total_to_be_repaid' => $totalToBeRepaid,
            ],
            'repayments' => [
                'total_repayments' => $totalRepayments,
                'count' => Repayment::count(),
            ],
            'financials' => [
                'total_investments' => $totalInvestments,
                'total_fees_collected' => $totalFeesCollected,
                'total_disbursed' => $totalDisbursed,
                'total_repayments' => $totalRepayments,
                'outstanding_balance' => $outstandingBalance,
                'available_for_lending' => $availableFunds,
                'total_fund_value' => $totalInvestments + $totalFeesCollected + $totalRepayments,
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function custom(Request $request)
    {
        $startDate = Carbon::parse($request->get('start_date'));
        $endDate = Carbon::parse($request->get('end_date'));

        $data = $this->getReportData($startDate, $endDate);

        return response()->json([
            'success' => true,
            'report_type' => 'Custom',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $data
        ]);
    }

    private function getReportData($startDate, $endDate)
    {
        // Investments
        $investments = Investment::whereBetween('investment_date', [$startDate, $endDate])->sum('amount');
        $investmentCount = Investment::whereBetween('investment_date', [$startDate, $endDate])->count();

        // Loans
        $loans = Loan::whereBetween('loan_date', [$startDate, $endDate]);
        $loansDisbursed = $loans->sum('net_disbursed');
        $loansCount = $loans->count();
        $feesCollected = $loans->sum('upfront_fee');

        // Repayments
        $repayments = Repayment::whereBetween('payment_date', [$startDate, $endDate])->sum('amount');
        $repaymentsCount = Repayment::whereBetween('payment_date', [$startDate, $endDate])->count();

        // Members
        $newMembers = Member::whereBetween('date_joined', [$startDate, $endDate])->count();
        $activeMembers = Member::active()->count();

        // Outstanding balance (all active loans)
        $totalToBeRepaid = Loan::where('status', 'Active')->sum('total_to_repay');
        $totalRepaidActive = Repayment::whereHas('loan', function ($query) {
            $query->where('status', 'Active');
        })->sum('amount');
        $outstandingBalance = $totalToBeRepaid - $totalRepaidActive;

        // Net cash flow for the period
        $netCashFlow = $investments + $feesCollected + $repayments - $loansDisbursed;

        return [
            'investments' => [
                'total' => $investments,
                'count' => $investmentCount,
            ],
            'loans' => [
                'disbursed' => $loansDisbursed,
                'count' => $loansCount,
                'fees_collected' => $feesCollected,
            ],
            'repayments' => [
                'total' => $repayments,
                'count' => $repaymentsCount,
            ],
            'members' => [
                'new_members' => $newMembers,
                'active_members' => $activeMembers,
            ],
            'financials' => [
                'outstanding_balance' => $outstandingBalance,
                'net_cash_flow' => $netCashFlow,
            ]
        ];
    }

    private function getQuarterDates($year, $quarter)
    {
        $quarterMap = [
            'Q1' => ['start' => 1, 'end' => 3],
            'Q2' => ['start' => 4, 'end' => 6],
            'Q3' => ['start' => 7, 'end' => 9],
            'Q4' => ['start' => 10, 'end' => 12],
        ];

        $months = $quarterMap[$quarter];
        
        return [
            'start' => Carbon::create($year, $months['start'], 1)->startOfDay(),
            'end' => Carbon::create($year, $months['end'], 1)->endOfMonth()->endOfDay(),
        ];
    }

    private function getBiannualDates($year, $period)
    {
        if ($period === 'H1') {
            return [
                'start' => Carbon::create($year, 1, 1)->startOfDay(),
                'end' => Carbon::create($year, 6, 30)->endOfDay(),
            ];
        } else {
            return [
                'start' => Carbon::create($year, 7, 1)->startOfDay(),
                'end' => Carbon::create($year, 12, 31)->endOfDay(),
            ];
        }
    }

    public function memberStatement($memberId, Request $request)
    {
        $member = Member::findOrFail($memberId);
        
        $startDate = $request->has('start_date') 
            ? Carbon::parse($request->start_date) 
            : Carbon::now()->subYear();
        $endDate = $request->has('end_date') 
            ? Carbon::parse($request->end_date) 
            : Carbon::now();

        $investments = Investment::where('member_id', $memberId)
            ->whereBetween('investment_date', [$startDate, $endDate])
            ->get();

        $loans = Loan::where('member_id', $memberId)
            ->whereBetween('loan_date', [$startDate, $endDate])
            ->with('repayments')
            ->get();

        $repayments = Repayment::where('member_id', $memberId)
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get();

        return response()->json([
            'success' => true,
            'member' => $member,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'statement' => [
                'investments' => $investments,
                'loans' => $loans,
                'repayments' => $repayments,
            ],
            'summary' => [
                'total_investments' => $investments->sum('amount'),
                'total_loans' => $loans->sum('loan_amount'),
                'total_repayments' => $repayments->sum('amount'),
                'current_outstanding' => $member->total_outstanding,
            ]
        ]);
    }
}
