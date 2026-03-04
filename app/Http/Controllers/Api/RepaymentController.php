<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Repayment;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RepaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Repayment::with(['loan', 'member']);

        // Filter by loan
        if ($request->has('loan_id')) {
            $query->where('loan_id', $request->loan_id);
        }

        // Filter by member
        if ($request->has('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->forPeriod($request->start_date, $request->end_date);
        }

        $repayments = $query->orderBy('payment_date', 'desc')
                            ->paginate($request->get('per_page', 15));

        return response()->json($repayments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repayment_id' => 'required|string|unique:repayments,repayment_id',
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the loan
        $loan = Loan::findOrFail($request->loan_id);

        // Check if loan is active
        if ($loan->status !== 'Active') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot add repayment to a loan that is not active'
            ], 422);
        }

        // Check if repayment amount exceeds remaining balance
        if ($request->amount > $loan->remaining_balance) {
            return response()->json([
                'success' => false,
                'message' => 'Repayment amount exceeds remaining loan balance',
                'data' => [
                    'remaining_balance' => $loan->remaining_balance,
                    'attempted_amount' => $request->amount,
                ]
            ], 422);
        }

        // Create repayment with member_id from loan
        $repayment = Repayment::create([
            'repayment_id' => $request->repayment_id,
            'loan_id' => $request->loan_id,
            'member_id' => $loan->member_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'notes' => $request->notes,
        ]);

        // Reload loan to get updated status
        $loan->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Repayment recorded successfully',
            'data' => [
                'repayment' => $repayment->load(['loan', 'member']),
                'loan_status' => $loan->status,
                'remaining_balance' => $loan->remaining_balance,
            ]
        ], 201);
    }

    public function show($id)
    {
        $repayment = Repayment::with(['loan', 'member'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $repayment
        ]);
    }

    public function update(Request $request, $id)
    {
        $repayment = Repayment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'repayment_id' => 'sometimes|string|unique:repayments,repayment_id,' . $id,
            'amount' => 'sometimes|numeric|min:0',
            'payment_date' => 'sometimes|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // If amount is being updated, check the new amount
        if ($request->has('amount')) {
            $loan = $repayment->loan;
            $otherRepayments = $loan->repayments()->where('id', '!=', $id)->sum('amount');
            $newTotal = $otherRepayments + $request->amount;
            
            if ($newTotal > $loan->total_to_repay) {
                return response()->json([
                    'success' => false,
                    'message' => 'Updated amount would exceed total loan amount'
                ], 422);
            }
        }

        $repayment->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Repayment updated successfully',
            'data' => $repayment->load(['loan', 'member'])
        ]);
    }

    public function destroy($id)
    {
        $repayment = Repayment::findOrFail($id);
        $loan = $repayment->loan;
        
        $repayment->delete();

        // Update loan status if needed
        if ($loan->status === 'Completed' && $loan->remaining_balance > 0) {
            $loan->status = 'Active';
            $loan->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Repayment deleted successfully'
        ]);
    }

    public function byLoan($loanId)
    {
        $loan = Loan::findOrFail($loanId);
        $repayments = $loan->repayments()->with('member')->orderBy('payment_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'loan' => $loan,
                'repayments' => $repayments,
                'total_repaid' => $loan->total_repaid,
                'remaining_balance' => $loan->remaining_balance,
            ]
        ]);
    }

    public function statistics(Request $request)
    {
        $query = Repayment::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->forPeriod($request->start_date, $request->end_date);
        }

        $stats = [
            'total_repayments' => $query->sum('amount'),
            'count' => $query->count(),
            'average_repayment' => $query->avg('amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
