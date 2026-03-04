@extends('layouts.app')

@section('title', 'Record Repayment - Kikundi Management')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <a href="{{ url('/repayments') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-4">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Repayments
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Record Loan Repayment</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Record a payment towards a loan</p>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <form id="repayment-form" class="p-6 space-y-6">
            <!-- Loan Selection -->
            <div>
                <label for="loan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Loan <span class="text-red-500">*</span>
                </label>
                <select id="loan_id" name="loan_id" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Select a loan</option>
                </select>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Only active loans with outstanding balance are shown</p>
            </div>

            <!-- Loan Details Display -->
            <div id="loan-details" class="hidden space-y-3 p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Loan Information</h3>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Member:</span>
                        <span class="font-semibold text-gray-900 dark:text-white ml-2" id="loan-member">-</span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Loan ID:</span>
                        <span class="font-semibold text-gray-900 dark:text-white ml-2" id="loan-loan-id">-</span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Loan Amount:</span>
                        <span class="font-semibold text-gray-900 dark:text-white ml-2" id="loan-amount">TZS 0</span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Total Repaid:</span>
                        <span class="font-semibold text-green-600 dark:text-green-400 ml-2" id="loan-repaid">TZS 0</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-600 dark:text-gray-400">Outstanding Balance:</span>
                        <span class="font-bold text-red-600 dark:text-red-400 text-lg ml-2" id="loan-balance">TZS 0</span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Due Date:</span>
                        <span class="font-semibold ml-2" id="loan-due-date">-</span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="ml-2" id="loan-status-badge">-</span>
                    </div>
                </div>
            </div>

            <!-- Transaction ID -->
            <div>
                <label for="transaction_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Transaction ID <span class="text-red-500">*</span>
                </label>
                <input type="text" id="transaction_id" name="transaction_id" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="e.g., REP001 or MPESA123456">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Reference number for this repayment</p>
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Repayment Amount (TZS) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="amount" name="amount" step="0.01" min="0.01" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="0.00">
                <div class="mt-2 flex gap-2">
                    <button type="button" onclick="setFullAmount()" class="text-xs px-3 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300">
                        Pay Full Balance
                    </button>
                    <button type="button" onclick="setHalfAmount()" class="text-xs px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                        Pay Half
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Remaining after payment: <span id="remaining-balance" class="font-semibold">TZS 0.00</span>
                </p>
            </div>

            <!-- Repayment Date -->
            <div>
                <label for="repayment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Repayment Date <span class="text-red-500">*</span>
                </label>
                <input type="date" id="repayment_date" name="repayment_date" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Payment Method -->
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Payment Method
                </label>
                <select id="payment_method" name="payment_method"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Select method</option>
                    <option value="Cash">Cash</option>
                    <option value="M-Pesa">M-Pesa</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Notes
                </label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Any additional notes about this repayment"></textarea>
            </div>

            <!-- Error Message -->
            <div id="error-message" class="hidden p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg"></div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ url('/repayments') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                    Record Repayment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let loans = [];
    let selectedLoan = null;

    // Set default date to today
    document.getElementById('repayment_date').valueAsDate = new Date();

    // Load active loans with outstanding balance
    async function loadLoans() {
        try {
            const response = await fetch('/api/loans');
            const data = await response.json();

            // Filter for active loans with balance
            loans = (data.data || []).filter(loan => {
                const balance = parseFloat(loan.total_to_repay) - (parseFloat(loan.total_repaid) || 0);
                return loan.status === 'Active' && balance > 0;
            });

            const select = document.getElementById('loan_id');
            loans.forEach(loan => {
                const balance = parseFloat(loan.total_to_repay) - (parseFloat(loan.total_repaid) || 0);
                const option = document.createElement('option');
                option.value = loan.id;
                option.textContent = `${loan.loan_id} - ${loan.member?.full_name} (Balance: TZS ${balance.toLocaleString()})`;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading loans:', error);
        }
    }

    // Handle loan selection
    document.getElementById('loan_id').addEventListener('change', function() {
        const loanId = parseInt(this.value);
        selectedLoan = loans.find(loan => loan.id === loanId);

        if (!selectedLoan) {
            document.getElementById('loan-details').classList.add('hidden');
            return;
        }

        const totalRepaid = parseFloat(selectedLoan.total_repaid) || 0;
        const balance = parseFloat(selectedLoan.total_to_repay) - totalRepaid;
        const dueDate = new Date(selectedLoan.due_date);
        const isOverdue = dueDate < new Date();

        document.getElementById('loan-member').textContent = selectedLoan.member?.full_name || 'N/A';
        document.getElementById('loan-loan-id').textContent = selectedLoan.loan_id;
        document.getElementById('loan-amount').textContent = `TZS ${parseFloat(selectedLoan.total_to_repay).toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('loan-repaid').textContent = `TZS ${totalRepaid.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('loan-balance').textContent = `TZS ${balance.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('loan-due-date').textContent = dueDate.toLocaleDateString();

        const statusBadge = document.getElementById('loan-status-badge');
        if (isOverdue) {
            statusBadge.innerHTML = '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">OVERDUE</span>';
            statusBadge.classList.add('text-red-600', 'dark:text-red-400');
        } else {
            statusBadge.innerHTML = '<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Active</span>';
            statusBadge.classList.remove('text-red-600', 'dark:text-red-400');
        }

        document.getElementById('loan-details').classList.remove('hidden');
        calculateRemaining();
    });

    // Calculate remaining balance after repayment
    function calculateRemaining() {
        if (!selectedLoan) return;

        const totalRepaid = parseFloat(selectedLoan.total_repaid) || 0;
        const balance = parseFloat(selectedLoan.total_to_repay) - totalRepaid;
        const repaymentAmount = parseFloat(document.getElementById('amount').value) || 0;
        const remaining = balance - repaymentAmount;

        document.getElementById('remaining-balance').textContent = `TZS ${Math.max(0, remaining).toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
    }

    // Set full amount
    function setFullAmount() {
        if (!selectedLoan) return;

        const totalRepaid = parseFloat(selectedLoan.total_repaid) || 0;
        const balance = parseFloat(selectedLoan.total_to_repay) - totalRepaid;
        document.getElementById('amount').value = balance.toFixed(2);
        calculateRemaining();
    }

    // Set half amount
    function setHalfAmount() {
        if (!selectedLoan) return;

        const totalRepaid = parseFloat(selectedLoan.total_repaid) || 0;
        const balance = parseFloat(selectedLoan.total_to_repay) - totalRepaid;
        document.getElementById('amount').value = (balance / 2).toFixed(2);
        calculateRemaining();
    }

    // Update remaining balance when amount changes
    document.getElementById('amount').addEventListener('input', calculateRemaining);

    // Form submission
    document.getElementById('repayment-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const errorDiv = document.getElementById('error-message');
        errorDiv.classList.add('hidden');

        if (!selectedLoan) {
            errorDiv.textContent = 'Please select a loan';
            errorDiv.classList.remove('hidden');
            return;
        }

        const amount = parseFloat(document.getElementById('amount').value);
        const totalRepaid = parseFloat(selectedLoan.total_repaid) || 0;
        const balance = parseFloat(selectedLoan.total_to_repay) - totalRepaid;

        if (amount > balance) {
            errorDiv.textContent = `Repayment amount cannot exceed outstanding balance of TZS ${balance.toLocaleString()}`;
            errorDiv.classList.remove('hidden');
            return;
        }

        const formData = {
            loan_id: parseInt(document.getElementById('loan_id').value),
            transaction_id: document.getElementById('transaction_id').value,
            amount: amount,
            repayment_date: document.getElementById('repayment_date').value,
            payment_method: document.getElementById('payment_method').value,
            notes: document.getElementById('notes').value
        };

        try {
            const response = await fetch('/api/repayments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                alert('Repayment recorded successfully!');
                window.location.href = '/repayments';
            } else {
                errorDiv.textContent = data.message || 'Error recording repayment. Please check your input.';
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            errorDiv.textContent = 'An error occurred. Please try again.';
            errorDiv.classList.remove('hidden');
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', loadLoans);
</script>
@endsection
