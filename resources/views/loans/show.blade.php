@extends('layouts.app')

@section('title', 'Loan Details - Kikundi Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ url('/loans') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-2">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Loans
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white" id="loan-id">Loading...</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" id="member-name"></p>
        </div>
        <div class="flex gap-3">
            <a href="#" id="member-link" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                View Member
            </a>
            <a href="/repayments/create" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                Record Repayment
            </a>
        </div>
    </div>

    <!-- Loan Summary Cards -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <!-- Loan Amount -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Loan Amount</h2>
            <p class="text-2xl font-bold text-gray-900 dark:text-white" id="loan-amount">TZS 0</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Fee: <span id="upfront-fee">TZS 0</span>
            </p>
        </div>

        <!-- Net Disbursed -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Net Disbursed</h2>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400" id="net-disbursed">TZS 0</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Amount received</p>
        </div>

        <!-- Total Repaid -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Total Repaid</h2>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="total-repaid">TZS 0</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Progress: <span id="repayment-progress">0%</span>
            </p>
        </div>

        <!-- Remaining Balance -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Remaining Balance</h2>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400" id="remaining-balance">TZS 0</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">To be repaid</p>
        </div>
    </div>

    <!-- Loan Details -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Loan Information -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Loan Information</h2>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Loan Date</dt>
                    <dd class="text-sm text-gray-900 dark:text-white" id="loan-date">-</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Due Date</dt>
                    <dd class="text-sm text-gray-900 dark:text-white" id="due-date">-</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd id="status-badge">-</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Days Remaining</dt>
                    <dd class="text-sm text-gray-900 dark:text-white" id="days-remaining">-</dd>
                </div>
            </dl>
        </div>

        <!-- Notes -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notes</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400" id="notes">-</p>
        </div>
    </div>

    <!-- Repayment History -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Repayment History</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Payment Method</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="repayments-tbody">
                        <tr><td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const loanId = window.location.pathname.split('/').pop();

    async function loadLoanDetails() {
        try {
            const response = await fetch(`/api/loans/${loanId}`);
            const result = await response.json();
            const loan = result.data;

            // Header
            document.getElementById('loan-id').textContent = `Loan ${loan.loan_id}`;
            document.getElementById('member-name').textContent = `Member: ${loan.member?.full_name || 'N/A'}`;
            document.getElementById('member-link').href = `/members/${loan.member_id}`;

            // Summary cards
            document.getElementById('loan-amount').textContent = `TZS ${parseFloat(loan.loan_amount).toLocaleString()}`;
            document.getElementById('upfront-fee').textContent = `TZS ${parseFloat(loan.upfront_fee).toLocaleString()}`;
            document.getElementById('net-disbursed').textContent = `TZS ${parseFloat(loan.net_disbursed).toLocaleString()}`;

            const totalRepaid = parseFloat(loan.total_repaid || 0);
            const totalToRepay = parseFloat(loan.total_to_repay);
            const remaining = totalToRepay - totalRepaid;
            const progress = totalToRepay > 0 ? ((totalRepaid / totalToRepay) * 100).toFixed(1) : '0.0';

            document.getElementById('total-repaid').textContent = `TZS ${totalRepaid.toLocaleString()}`;
            document.getElementById('repayment-progress').textContent = `${progress}%`;
            document.getElementById('remaining-balance').textContent = `TZS ${remaining.toLocaleString()}`;

            // Loan information
            document.getElementById('loan-date').textContent = new Date(loan.loan_date).toLocaleDateString();
            document.getElementById('due-date').textContent = new Date(loan.due_date).toLocaleDateString();
            document.getElementById('notes').textContent = loan.notes || 'No notes available';

            // Status badge
            const statusBadge = document.getElementById('status-badge');
            const isOverdue = loan.status === 'Active' && new Date(loan.due_date) < new Date();
            statusBadge.innerHTML = `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                loan.status === 'Active' ? (isOverdue ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') :
                loan.status === 'Completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
            }">${loan.status}${isOverdue ? ' (Overdue)' : ''}</span>`;

            // Days remaining
            const today = new Date();
            const dueDate = new Date(loan.due_date);
            const diffTime = dueDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (loan.status === 'Completed') {
                document.getElementById('days-remaining').textContent = 'Completed';
            } else if (diffDays < 0) {
                document.getElementById('days-remaining').innerHTML = `<span class="text-red-600 dark:text-red-400 font-medium">${Math.abs(diffDays)} days overdue</span>`;
            } else {
                document.getElementById('days-remaining').textContent = `${diffDays} days`;
            }

            // Render repayments from included relationship
            renderRepayments(loan.repayments || []);

        } catch (error) {
            console.error('Error loading loan:', error);
        }
    }

    function renderRepayments(repayments) {
        const tbody = document.getElementById('repayments-tbody');

        if (repayments.length > 0) {
            tbody.innerHTML = repayments.map(repayment => `
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                        ${new Date(repayment.payment_date || repayment.repayment_date).toLocaleDateString()}
                    </td>
                    <td class="px-4 py-3 text-sm font-semibold text-green-600 dark:text-green-400">
                        TZS ${parseFloat(repayment.amount).toLocaleString()}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                        ${repayment.payment_method || 'N/A'}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                        ${repayment.notes || '-'}
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No repayments yet</td></tr>';
        }
    }

    document.addEventListener('DOMContentLoaded', loadLoanDetails);
</script>
@endsection
