<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with(['member', 'repayments']);

        // Filter by member
        if ($request->has('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter overdue loans
        if ($request->has('overdue') && $request->overdue) {
            $query->overdue();
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->forPeriod($request->start_date, $request->end_date);
        }

        $loans = $query->orderBy('loan_date', 'desc')
                       ->paginate($request->get('per_page', 15));

        // Add computed fields
        $loans->getCollection()->transform(function ($loan) {
            $loan->total_repaid = $loan->total_repaid;
            $loan->remaining_balance = $loan->remaining_balance;
            $loan->is_overdue = $loan->is_overdue;
            $loan->days_overdue = $loan->days_overdue;
            return $loan;
        });

        return response()->json($loans);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loan_id' => 'required|string|unique:loans,loan_id',
            'member_id' => 'required|exists:members,id',
            'loan_amount' => 'required|numeric|min:0',
            'loan_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if member has an active loan
        $member = Member::findOrFail($request->member_id);

        if ($member->has_active_loan) {
            return response()->json([
                'success' => false,
                'message' => 'Member has an active loan that must be fully repaid before taking a new loan',
            ], 422);
        }

        // Check if member is eligible for this loan amount
        if (!$member->canBorrow($request->loan_amount)) {
            return response()->json([
                'success' => false,
                'message' => 'Loan amount exceeds maximum eligible amount',
                'data' => [
                    'total_investment' => $member->total_investment,
                    'max_loan_eligible' => $member->max_loan_eligible,
                    'current_outstanding' => $member->total_outstanding,
                    'requested_amount' => $request->loan_amount,
                ]
            ], 422);
        }

        // Create loan (automatic calculations happen in model boot method)
        $loan = Loan::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Loan processed successfully',
            'data' => $loan->load('member')
        ], 201);
    }

    public function show($id)
    {
        $loan = Loan::with(['member', 'repayments'])->findOrFail($id);
        
        $loan->total_repaid = $loan->total_repaid;
        $loan->remaining_balance = $loan->remaining_balance;
        $loan->is_overdue = $loan->is_overdue;
        $loan->days_overdue = $loan->days_overdue;

        return response()->json([
            'success' => true,
            'data' => $loan
        ]);
    }

    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'loan_id' => 'sometimes|string|unique:loans,loan_id,' . $id,
            'status' => 'sometimes|in:Active,Completed,Defaulted',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $loan->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Loan updated successfully',
            'data' => $loan->load('member')
        ]);
    }

    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        
        // Check if loan has repayments
        if ($loan->repayments()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete loan with existing repayments'
            ], 422);
        }

        $loan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loan deleted successfully'
        ]);
    }

    public function statistics(Request $request)
    {
        $query = Loan::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->forPeriod($request->start_date, $request->end_date);
        }

        $stats = [
            'total_loans' => $query->count(),
            'active_loans' => $query->clone()->where('status', 'Active')->count(),
            'completed_loans' => $query->clone()->where('status', 'Completed')->count(),
            'defaulted_loans' => $query->clone()->where('status', 'Defaulted')->count(),
            'overdue_loans' => $query->clone()->overdue()->count(),
            'total_disbursed' => $query->clone()->sum('net_disbursed'),
            'total_fees_collected' => $query->clone()->sum('upfront_fee'),
            'total_to_be_repaid' => $query->clone()->sum('total_to_repay'),
            'total_repaid' => \App\Models\Repayment::query()
                                ->when($request->has('start_date') && $request->has('end_date'), function ($q) use ($request) {
                                    return $q->forPeriod($request->start_date, $request->end_date);
                                })
                                ->sum('amount'),
        ];

        $stats['outstanding_balance'] = $stats['total_to_be_repaid'] - $stats['total_repaid'];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function overdueLoans()
    {
        $loans = Loan::with(['member', 'repayments'])
                     ->overdue()
                     ->get()
                     ->map(function ($loan) {
                         $loan->total_repaid = $loan->total_repaid;
                         $loan->remaining_balance = $loan->remaining_balance;
                         $loan->days_overdue = $loan->days_overdue;
                         return $loan;
                     });

        return response()->json([
            'success' => true,
            'data' => $loans
        ]);
    }
}
