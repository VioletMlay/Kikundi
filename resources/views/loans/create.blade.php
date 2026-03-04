@extends('layouts.app')

@section('title', 'Create Loan - Kikundi Management')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <a href="{{ url('/loans') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-4">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Loans
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Loan</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Issue a new loan to a member</p>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <form id="loan-form" class="p-6 space-y-6">
            <!-- Member -->
            <div>
                <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Member <span class="text-red-500">*</span>
                </label>
                <select id="member_id" name="member_id" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Select a member</option>
                </select>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select the member who will receive the loan</p>
            </div>

            <!-- Active Loan Warning -->
            <div id="active-loan-warning" class="hidden p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <h3 class="text-sm font-medium text-red-900 dark:text-red-200">Cannot Issue Loan</h3>
                <p class="mt-1 text-sm text-red-700 dark:text-red-300">This member has an active loan that must be fully repaid before taking a new loan.</p>
            </div>

            <!-- Member Eligibility Info -->
            <div id="member-info" class="hidden p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-2">Member Eligibility</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-blue-700 dark:text-blue-300">Total Investments:</span>
                        <span class="font-semibold text-blue-900 dark:text-blue-100 ml-2" id="total-investments">TZS 0</span>
                    </div>
                    <div>
                        <span class="text-blue-700 dark:text-blue-300">Max Loan Eligible:</span>
                        <span class="font-semibold text-blue-900 dark:text-blue-100 ml-2" id="max-loan-amount">TZS 0</span>
                    </div>
                    <div>
                        <span class="text-blue-700 dark:text-blue-300">Current Outstanding:</span>
                        <span class="font-semibold text-blue-900 dark:text-blue-100 ml-2" id="current-outstanding">TZS 0</span>
                    </div>
                    <div>
                        <span class="text-blue-700 dark:text-blue-300">Available to Borrow:</span>
                        <span class="font-semibold text-green-700 dark:text-green-300 ml-2" id="available-to-borrow">TZS 0</span>
                    </div>
                </div>
            </div>

            <!-- Loan ID -->
            <div>
                <label for="loan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Loan ID <span class="text-red-500">*</span>
                </label>
                <input type="text" id="loan_id" name="loan_id" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="e.g., LOAN001">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Unique identifier for this loan</p>
            </div>

            <!-- Loan Amount -->
            <div>
                <label for="loan_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Loan Amount (TZS) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="loan_amount" name="loan_amount" step="0.01" min="0" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="0.00">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Amount cannot exceed maximum loan amount</p>
            </div>

            <!-- Calculations Display -->
            <div class="p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg space-y-3">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Loan Calculations</h3>

                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Loan Amount:</span>
                    <span class="font-semibold text-gray-900 dark:text-white" id="display-loan-amount">TZS 0.00</span>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Upfront Fee (<span id="fee-percentage">10</span>%):</span>
                    <span class="font-semibold text-red-600 dark:text-red-400" id="display-upfront-fee">TZS 0.00</span>
                </div>

                <div class="pt-3 border-t border-gray-300 dark:border-gray-600 flex justify-between items-center">
                    <span class="text-gray-900 dark:text-white font-medium">Net Disbursed:</span>
                    <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400" id="display-net-disbursed">TZS 0.00</span>
                </div>

                <div class="flex justify-between items-center text-sm pt-2">
                    <span class="text-gray-600 dark:text-gray-400">Total to Repay:</span>
                    <span class="font-semibold text-gray-900 dark:text-white" id="display-total-repay">TZS 0.00</span>
                </div>
            </div>

            <!-- Loan Date -->
            <div>
                <label for="loan_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Loan Date <span class="text-red-500">*</span>
                </label>
                <input type="date" id="loan_date" name="loan_date" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Loan Period -->
            <div>
                <label for="loan_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Loan Period (Months) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="loan_period" name="loan_period" min="1" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="6">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Due date: <span id="display-due-date" class="font-medium">Select loan date and period</span>
                </p>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Notes
                </label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Any additional notes about this loan"></textarea>
            </div>

            <!-- Error Message -->
            <div id="error-message" class="hidden p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg"></div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ url('/loans') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                    Create Loan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let settings = {};
    let members = [];

    // Set default date to today
    document.getElementById('loan_date').valueAsDate = new Date();

    // Load settings
    async function loadSettings() {
        try {
            const response = await fetch('/api/settings');
            const data = await response.json();
            settings = data.data || {};

            // Update fee percentage display
            const feePercentage = settings.upfront_fee_percentage || 10;
            document.getElementById('fee-percentage').textContent = feePercentage;

            // Set default loan period
            document.getElementById('loan_period').value = settings.loan_period_months || 6;
        } catch (error) {
            console.error('Error loading settings:', error);
        }
    }

    // Load members
    async function loadMembers() {
        try {
            const response = await fetch('/api/members');
            const data = await response.json();
            members = data.data || [];

            const select = document.getElementById('member_id');
            members.forEach(member => {
                if (member.status === 'Active') {
                    const option = document.createElement('option');
                    option.value = member.id;
                    option.textContent = `${member.full_name} (${member.member_id})`;
                    select.appendChild(option);
                }
            });
        } catch (error) {
            console.error('Error loading members:', error);
        }
    }

    // Calculate loan details
    function calculateLoan() {
        const loanAmount = parseFloat(document.getElementById('loan_amount').value) || 0;
        const feePercentage = parseFloat(settings.upfront_fee_percentage) || 10;

        const upfrontFee = loanAmount * (feePercentage / 100);
        const netDisbursed = loanAmount - upfrontFee;
        const totalToRepay = loanAmount;

        document.getElementById('display-loan-amount').textContent = `TZS ${loanAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        document.getElementById('display-upfront-fee').textContent = `TZS ${upfrontFee.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        document.getElementById('display-net-disbursed').textContent = `TZS ${netDisbursed.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        document.getElementById('display-total-repay').textContent = `TZS ${totalToRepay.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    }

    // Calculate due date
    function calculateDueDate() {
        const loanDate = document.getElementById('loan_date').value;
        const loanPeriod = parseInt(document.getElementById('loan_period').value) || 0;

        if (loanDate && loanPeriod > 0) {
            const date = new Date(loanDate);
            date.setMonth(date.getMonth() + loanPeriod);
            document.getElementById('display-due-date').textContent = date.toLocaleDateString();
        }
    }

    // Handle member selection
    document.getElementById('member_id').addEventListener('change', async function() {
        const memberId = this.value;
        const memberInfo = document.getElementById('member-info');
        const activeLoanWarning = document.getElementById('active-loan-warning');
        const submitBtn = document.querySelector('#loan-form button[type="submit"]');

        memberInfo.classList.add('hidden');
        activeLoanWarning.classList.add('hidden');
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

        if (!memberId) return;

        try {
            const response = await fetch(`/api/members/${memberId}/check-eligibility`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            const result = await response.json();
            const data = result.data;

            if (data.has_active_loan) {
                activeLoanWarning.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            const totalInvestments = parseFloat(data.total_investment || 0);
            const maxLoanEligible = parseFloat(data.max_loan_eligible || 0);
            const outstanding = parseFloat(data.current_outstanding || 0);
            const available = parseFloat(data.available_to_borrow || 0);

            document.getElementById('total-investments').textContent = `TZS ${totalInvestments.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
            document.getElementById('max-loan-amount').textContent = `TZS ${maxLoanEligible.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
            document.getElementById('current-outstanding').textContent = `TZS ${outstanding.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
            document.getElementById('available-to-borrow').textContent = `TZS ${available.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
            memberInfo.classList.remove('hidden');

            // Set max attribute on loan amount input
            document.getElementById('loan_amount').max = available;
        } catch (error) {
            console.error('Error checking member eligibility:', error);
        }
    });

    // Update calculations when loan amount changes
    document.getElementById('loan_amount').addEventListener('input', calculateLoan);

    // Update due date when loan date or period changes
    document.getElementById('loan_date').addEventListener('change', calculateDueDate);
    document.getElementById('loan_period').addEventListener('change', calculateDueDate);

    // Form submission
    document.getElementById('loan-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const errorDiv = document.getElementById('error-message');
        errorDiv.classList.add('hidden');

        const loanAmount = parseFloat(document.getElementById('loan_amount').value);
        const maxLoanAmount = parseFloat(document.getElementById('loan_amount').max);

        if (maxLoanAmount && loanAmount > maxLoanAmount) {
            errorDiv.textContent = `Loan amount cannot exceed maximum loan amount of TZS ${maxLoanAmount.toLocaleString()}`;
            errorDiv.classList.remove('hidden');
            return;
        }

        const formData = {
            member_id: parseInt(document.getElementById('member_id').value),
            loan_id: document.getElementById('loan_id').value,
            loan_amount: loanAmount,
            loan_date: document.getElementById('loan_date').value,
            loan_period_months: parseInt(document.getElementById('loan_period').value),
            notes: document.getElementById('notes').value
        };

        try {
            const response = await fetch('/api/loans', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                alert('Loan created successfully!');
                window.location.href = '/loans';
            } else {
                errorDiv.textContent = data.message || 'Error creating loan. Please check your input.';
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            errorDiv.textContent = 'An error occurred. Please try again.';
            errorDiv.classList.remove('hidden');
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', async function() {
        await loadSettings();
        await loadMembers();
        calculateDueDate();
    });
</script>
@endsection
