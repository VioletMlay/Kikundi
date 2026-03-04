@extends('layouts.app')

@section('title', 'Settings - Kikundi Management')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">System Settings</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure loan and investment parameters</p>
    </div>

    <!-- Settings Form -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Loan Configuration</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure default loan parameters</p>
        </div>
        <form id="settings-form" class="p-6 space-y-6">
            <!-- Loan Multiplier -->
            <div>
                <label for="loan_multiplier" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Loan Multiplier <span class="text-red-500">*</span>
                </label>
                <input type="number" id="loan_multiplier" name="loan_multiplier" step="0.1" min="1" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="3.0">
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Maximum loan amount = Member's total investments × Loan multiplier
                </p>
                <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Example:</strong> If a member has invested TZS 10,000 and the loan multiplier is 3.0, they can borrow up to TZS 30,000
                    </p>
                </div>
            </div>

            <!-- Upfront Fee Percentage -->
            <div>
                <label for="upfront_fee_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Upfront Fee Percentage (%) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="upfront_fee_percentage" name="upfront_fee_percentage" step="0.1" min="0" max="100" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="10.0">
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Fee deducted from loan amount before disbursement
                </p>
                <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Example:</strong> For a TZS 10,000 loan with 10% upfront fee:
                    </p>
                    <ul class="mt-1 text-sm text-blue-800 dark:text-blue-200 list-disc list-inside ml-4">
                        <li>Upfront fee: TZS 1,000</li>
                        <li>Net disbursed: TZS 9,000</li>
                        <li>Total to repay: TZS 10,000</li>
                    </ul>
                </div>
            </div>

            <!-- Loan Period -->
            <div>
                <label for="loan_period_months" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Default Loan Period (Months) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="loan_period_months" name="loan_period_months" min="1" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="6">
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Default repayment period in months (can be adjusted per loan)
                </p>
                <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Example:</strong> If loan period is 6 months and loan date is Jan 1, 2024, the due date will be Jul 1, 2024
                    </p>
                </div>
            </div>

            <!-- Success Message -->
            <div id="success-message" class="hidden p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                Settings updated successfully!
            </div>

            <!-- Error Message -->
            <div id="error-message" class="hidden p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg"></div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <button type="button" onclick="resetForm()" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Reset
                </button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Current Settings Display -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Current Settings</h2>
        </div>
        <div class="p-6">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-5 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Loan Multiplier</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white" id="display-multiplier">-</dd>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-5 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Upfront Fee</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                        <span id="display-fee">-</span><span class="text-xl">%</span>
                    </dd>
                </div>
                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-5 rounded-lg">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Loan Period</dt>
                    <dd class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white">
                        <span id="display-period">-</span><span class="text-xl"> months</span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Impact Calculator -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Loan Calculator</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">See how settings affect loan calculations</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label for="calc-investment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Member Investment (TZS)
                    </label>
                    <input type="number" id="calc-investment" step="1000" min="0" value="10000"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="calc-loan-amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Loan Amount (TZS)
                    </label>
                    <input type="number" id="calc-loan-amount" step="1000" min="0" value="20000"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div class="mt-6 p-6 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Calculation Results</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Maximum Eligible Loan:</span>
                        <span class="text-lg font-semibold text-indigo-600 dark:text-indigo-400" id="calc-max-loan">TZS 0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Requested Loan Amount:</span>
                        <span class="text-lg font-semibold text-gray-900 dark:text-white" id="calc-requested">TZS 0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Upfront Fee:</span>
                        <span class="text-lg font-semibold text-red-600 dark:text-red-400" id="calc-fee">TZS 0</span>
                    </div>
                    <div class="pt-3 border-t border-indigo-300 dark:border-indigo-700 flex justify-between items-center">
                        <span class="font-medium text-gray-900 dark:text-white">Net Disbursed:</span>
                        <span class="text-xl font-bold text-green-600 dark:text-green-400" id="calc-net">TZS 0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-900 dark:text-white">Total to Repay:</span>
                        <span class="text-xl font-bold text-gray-900 dark:text-white" id="calc-repay">TZS 0</span>
                    </div>
                    <div id="calc-warning" class="hidden mt-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded text-sm text-red-800 dark:text-red-200">
                        Warning: Requested loan amount exceeds maximum eligible loan!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentSettings = {};

    // Load current settings
    async function loadSettings() {
        try {
            const response = await fetch('/api/settings');
            const data = await response.json();
            currentSettings = data.data || {};

            // Populate form
            document.getElementById('loan_multiplier').value = currentSettings.loan_multiplier || 3;
            document.getElementById('upfront_fee_percentage').value = currentSettings.upfront_fee_percentage || 10;
            document.getElementById('loan_period_months').value = currentSettings.loan_period_months || 6;

            // Update display
            updateDisplay();

            // Update calculator
            updateCalculator();
        } catch (error) {
            console.error('Error loading settings:', error);
        }
    }

    // Update display cards
    function updateDisplay() {
        document.getElementById('display-multiplier').textContent = currentSettings.loan_multiplier || '-';
        document.getElementById('display-fee').textContent = currentSettings.upfront_fee_percentage || '-';
        document.getElementById('display-period').textContent = currentSettings.loan_period_months || '-';
    }

    // Update calculator
    function updateCalculator() {
        const investment = parseFloat(document.getElementById('calc-investment').value) || 0;
        const loanAmount = parseFloat(document.getElementById('calc-loan-amount').value) || 0;
        const multiplier = parseFloat(document.getElementById('loan_multiplier').value) || 3;
        const feePercentage = parseFloat(document.getElementById('upfront_fee_percentage').value) || 10;

        const maxLoan = investment * multiplier;
        const upfrontFee = loanAmount * (feePercentage / 100);
        const netDisbursed = loanAmount - upfrontFee;

        document.getElementById('calc-max-loan').textContent = `TZS ${maxLoan.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('calc-requested').textContent = `TZS ${loanAmount.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('calc-fee').textContent = `TZS ${upfrontFee.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('calc-net').textContent = `TZS ${netDisbursed.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('calc-repay').textContent = `TZS ${loanAmount.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;

        // Show warning if loan exceeds max
        const warningDiv = document.getElementById('calc-warning');
        if (loanAmount > maxLoan) {
            warningDiv.classList.remove('hidden');
        } else {
            warningDiv.classList.add('hidden');
        }
    }

    // Reset form
    function resetForm() {
        loadSettings();
        document.getElementById('success-message').classList.add('hidden');
        document.getElementById('error-message').classList.add('hidden');
    }

    // Form submission
    document.getElementById('settings-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const successDiv = document.getElementById('success-message');
        const errorDiv = document.getElementById('error-message');
        successDiv.classList.add('hidden');
        errorDiv.classList.add('hidden');

        const formData = {
            loan_multiplier: parseFloat(document.getElementById('loan_multiplier').value),
            upfront_fee_percentage: parseFloat(document.getElementById('upfront_fee_percentage').value),
            loan_period_months: parseInt(document.getElementById('loan_period_months').value)
        };

        try {
            const response = await fetch('/api/settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                currentSettings = data.data;
                updateDisplay();
                successDiv.classList.remove('hidden');
                setTimeout(() => successDiv.classList.add('hidden'), 3000);
            } else {
                errorDiv.textContent = data.message || 'Error updating settings. Please check your input.';
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            errorDiv.textContent = 'An error occurred. Please try again.';
            errorDiv.classList.remove('hidden');
        }
    });

    // Calculator input listeners
    document.getElementById('calc-investment').addEventListener('input', updateCalculator);
    document.getElementById('calc-loan-amount').addEventListener('input', updateCalculator);
    document.getElementById('loan_multiplier').addEventListener('input', updateCalculator);
    document.getElementById('upfront_fee_percentage').addEventListener('input', updateCalculator);

    // Initialize
    document.addEventListener('DOMContentLoaded', loadSettings);
</script>
@endsection
