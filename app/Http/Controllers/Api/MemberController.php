<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by member type
        if ($request->has('member_type')) {
            $query->where('member_type', $request->member_type);
        }

        // Search by name or phone
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%");
            });
        }

        $members = $query->with(['investments', 'loans', 'repayments'])
                         ->orderBy('created_at', 'desc')
                         ->paginate($request->get('per_page', 15));

        // Add computed fields
        $members->getCollection()->transform(function ($member) {
            $member->total_investment = $member->total_investment;
            $member->max_loan_eligible = $member->max_loan_eligible;
            $member->total_outstanding = $member->total_outstanding;
            return $member;
        });

        return response()->json($members);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|string|unique:members,member_id',
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'member_type' => 'required|in:Student,Worker',
            'date_joined' => 'required|date',
            'status' => 'sometimes|in:Active,Inactive',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $member = Member::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Member created successfully',
            'data' => $member
        ], 201);
    }

    public function show($id)
    {
        $member = Member::with(['investments', 'loans.repayments', 'repayments'])
                        ->findOrFail($id);

        $member->total_investment = $member->total_investment;
        $member->max_loan_eligible = $member->max_loan_eligible;
        $member->total_outstanding = $member->total_outstanding;
        $member->active_loans = $member->active_loans;

        return response()->json([
            'success' => true,
            'data' => $member
        ]);
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'member_id' => 'sometimes|string|unique:members,member_id,' . $id,
            'full_name' => 'sometimes|string|max:255',
            'phone_number' => 'sometimes|string|max:20',
            'member_type' => 'sometimes|in:Student,Worker',
            'date_joined' => 'sometimes|date',
            'status' => 'sometimes|in:Active,Inactive',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $member->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Member updated successfully',
            'data' => $member
        ]);
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully'
        ]);
    }

    public function statistics()
    {
        $stats = [
            'total_members' => Member::count(),
            'active_members' => Member::active()->count(),
            'inactive_members' => Member::inactive()->count(),
            'students' => Member::students()->count(),
            'workers' => Member::workers()->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function checkEligibility($id, Request $request)
    {
        $member = Member::findOrFail($id);

        $hasActiveLoan = $member->has_active_loan;
        $totalInvestment = $member->total_investment;
        $maxLoanEligible = $member->max_loan_eligible;
        $currentOutstanding = $member->total_outstanding;
        $availableToBorrow = max(0, $maxLoanEligible - $currentOutstanding);

        // If a specific amount is provided, check against it
        $requestedAmount = $request->loan_amount;
        $canBorrow = !$hasActiveLoan && $availableToBorrow > 0;

        if ($requestedAmount) {
            $canBorrow = !$hasActiveLoan && $member->canBorrow($requestedAmount);
        }

        $message = 'Member is eligible to borrow';
        if ($hasActiveLoan) {
            $message = 'Member has an active loan that must be fully repaid before taking a new loan';
        } elseif ($availableToBorrow <= 0) {
            $message = 'Member has no available borrowing capacity';
        } elseif ($requestedAmount && !$canBorrow) {
            $message = 'Requested amount exceeds available borrowing capacity';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'member_id' => $member->member_id,
                'member_name' => $member->full_name,
                'total_investment' => $totalInvestment,
                'max_loan_eligible' => $maxLoanEligible,
                'current_outstanding' => $currentOutstanding,
                'available_to_borrow' => $availableToBorrow,
                'has_active_loan' => $hasActiveLoan,
                'can_borrow' => $canBorrow,
                'message' => $message,
            ]
        ]);
    }
}
