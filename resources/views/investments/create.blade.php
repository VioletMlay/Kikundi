@extends('layouts.app')

@section('title', 'Record Investment - Kikundi Management')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <a href="{{ url('/investments') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-4">
            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Investments
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Record Investment</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Record a new member investment</p>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <form id="investment-form" class="p-6 space-y-6">
            <!-- Member -->
            <div>
                <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Member <span class="text-red-500">*</span>
                </label>
                <select id="member_id" name="member_id" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <option value="">Select a member</option>
                </select>
            </div>

            <!-- Transaction ID -->
            <div>
                <label for="transaction_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Transaction ID <span class="text-red-500">*</span>
                </label>
                <input type="text" id="transaction_id" name="transaction_id" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="e.g., INV001">
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Amount (TZS) <span class="text-red-500">*</span>
                </label>
                <input type="number" id="amount" name="amount" step="0.01" min="0" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="0.00">
            </div>

            <!-- Investment Date -->
            <div>
                <label for="investment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Investment Date <span class="text-red-500">*</span>
                </label>
                <input type="date" id="investment_date" name="investment_date" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Quarter and year will be calculated automatically</p>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Notes
                </label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Any additional notes about this investment"></textarea>
            </div>

            <!-- Error Message -->
            <div id="error-message" class="hidden p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg"></div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3">
                <a href="{{ url('/investments') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                    Record Investment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Set default date to today
    document.getElementById('investment_date').valueAsDate = new Date();

    // Load members
    async function loadMembers() {
        try {
            const response = await fetch('/api/members');
            const data = await response.json();

            const select = document.getElementById('member_id');
            data.data.forEach(member => {
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

    document.getElementById('investment-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const errorDiv = document.getElementById('error-message');
        errorDiv.classList.add('hidden');

        const formData = {
            member_id: document.getElementById('member_id').value,
            transaction_id: document.getElementById('transaction_id').value,
            amount: parseFloat(document.getElementById('amount').value),
            investment_date: document.getElementById('investment_date').value,
            notes: document.getElementById('notes').value
        };

        try {
            const response = await fetch('/api/investments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                alert('Investment recorded successfully!');
                window.location.href = '/investments';
            } else {
                errorDiv.textContent = data.message || 'Error recording investment. Please check your input.';
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            errorDiv.textContent = 'An error occurred. Please try again.';
            errorDiv.classList.remove('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', loadMembers);
</script>
@endsection
