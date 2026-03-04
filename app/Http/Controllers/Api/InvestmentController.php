<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvestmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Investment::with('member');

        // Filter by member
        if ($request->has('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        // Filter by year
        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        // Filter by quarter
        if ($request->has('quarter')) {
            $query->where('quarter', $request->quarter);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('investment_date', [$request->start_date, $request->end_date]);
        }

        $investments = $query->orderBy('investment_date', 'desc')
                             ->paginate($request->get('per_page', 15));

        return response()->json($investments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string|unique:investments,transaction_id',
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'investment_date' => 'required|date',
            'quarter' => 'sometimes|in:Q1,Q2,Q3,Q4',
            'year' => 'sometimes|integer',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify minimum investment based on member type
        $member = Member::findOrFail($request->member_id);
        $minInvestment = $member->member_type === 'Student' 
            ? \App\Models\Setting::get('min_investment_student', 5000)
            : \App\Models\Setting::get('min_investment_worker', 50000);

        if ($request->amount < $minInvestment) {
            return response()->json([
                'success' => false,
                'message' => "Minimum investment for {$member->member_type} members is {$minInvestment} Tsh"
            ], 422);
        }

        $investment = Investment::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Investment recorded successfully',
            'data' => $investment->load('member')
        ], 201);
    }

    public function show($id)
    {
        $investment = Investment::with('member')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $investment
        ]);
    }

    public function update(Request $request, $id)
    {
        $investment = Investment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'transaction_id' => 'sometimes|string|unique:investments,transaction_id,' . $id,
            'member_id' => 'sometimes|exists:members,id',
            'amount' => 'sometimes|numeric|min:0',
            'investment_date' => 'sometimes|date',
            'quarter' => 'sometimes|in:Q1,Q2,Q3,Q4',
            'year' => 'sometimes|integer',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $investment->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Investment updated successfully',
            'data' => $investment->load('member')
        ]);
    }

    public function destroy($id)
    {
        $investment = Investment::findOrFail($id);
        $investment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Investment deleted successfully'
        ]);
    }

    public function totalByMember($memberId)
    {
        $total = Investment::where('member_id', $memberId)->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'member_id' => $memberId,
                'total_investment' => $total
            ]
        ]);
    }

    public function statistics(Request $request)
    {
        $query = Investment::query();

        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        if ($request->has('quarter')) {
            $query->where('quarter', $request->quarter);
        }

        $stats = [
            'total_investments' => $query->sum('amount'),
            'count' => $query->count(),
            'average' => $query->avg('amount'),
            'by_quarter' => Investment::selectRaw('quarter, SUM(amount) as total')
                                      ->when($request->has('year'), function ($q) use ($request) {
                                          return $q->where('year', $request->year);
                                      })
                                      ->groupBy('quarter')
                                      ->get(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
