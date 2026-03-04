@extends('layouts.app')

@section('title', 'Repayments - Kikundi Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Repayments</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage loan repayments</p>
        </div>
        <a href="{{ url('/repayments/create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Record Repayment
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Repayments</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="total-repayments">0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount Collected</div>
            <div class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400" id="total-collected">TZS 0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">This Month</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="month-repayments">TZS 0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">This Week</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="week-repayments">TZS 0</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <input type="search" id="search" placeholder="Search by member or loan ID..." class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">

            <input type="date" id="date-from" placeholder="From date" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">

            <input type="date" id="date-to" placeholder="To date" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">

            <button onclick="resetFilters()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                Reset Filters
            </button>
        </div>
    </div>

    <!-- Repayments Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Repayments</h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Total: <span id="filtered-count" class="font-semibold">0</span> repayments
                </span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loan ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Repayment Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="repayments-tbody">
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let allRepayments = [];

    async function loadRepayments() {
        try {
            const response = await fetch('/api/repayments');
            const data = await response.json();
            allRepayments = data.data || [];

            displayRepayments(allRepayments);
            updateStatistics();
        } catch (error) {
            console.error('Error loading repayments:', error);
            document.getElementById('repayments-tbody').innerHTML = `
                <tr><td colspan="8" class="px-6 py-4 text-center text-red-500">Error loading repayments</td></tr>
            `;
        }
    }

    function displayRepayments(repayments) {
        const tbody = document.getElementById('repayments-tbody');

        if (repayments.length === 0) {
            tbody.innerHTML = `
                <tr><td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No repayments found</td></tr>
            `;
            document.getElementById('filtered-count').textContent = '0';
            return;
        }

        document.getElementById('filtered-count').textContent = repayments.length;

        tbody.innerHTML = repayments.map(repayment => `
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                    ${repayment.transaction_id}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    <a href="/members/${repayment.loan?.member_id}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                        ${repayment.loan?.member?.full_name || 'N/A'}
                    </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    <a href="/loans/${repayment.loan_id}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                        ${repayment.loan?.loan_id || 'N/A'}
                    </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                    TZS ${parseFloat(repayment.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${new Date(repayment.repayment_date).toLocaleDateString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${repayment.payment_method || '-'}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                    ${repayment.notes || '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="viewRepayment(${repayment.id})" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">View</button>
                    <button onclick="deleteRepayment(${repayment.id})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    function updateStatistics() {
        const totalRepayments = allRepayments.length;
        const totalCollected = allRepayments.reduce((sum, rep) => sum + parseFloat(rep.amount), 0);

        // Calculate this month
        const now = new Date();
        const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
        const monthRepayments = allRepayments.filter(rep => new Date(rep.repayment_date) >= startOfMonth);
        const monthTotal = monthRepayments.reduce((sum, rep) => sum + parseFloat(rep.amount), 0);

        // Calculate this week
        const startOfWeek = new Date(now);
        startOfWeek.setDate(now.getDate() - now.getDay());
        startOfWeek.setHours(0, 0, 0, 0);
        const weekRepayments = allRepayments.filter(rep => new Date(rep.repayment_date) >= startOfWeek);
        const weekTotal = weekRepayments.reduce((sum, rep) => sum + parseFloat(rep.amount), 0);

        document.getElementById('total-repayments').textContent = totalRepayments;
        document.getElementById('total-collected').textContent = `TZS ${totalCollected.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('month-repayments').textContent = `TZS ${monthTotal.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        document.getElementById('week-repayments').textContent = `TZS ${weekTotal.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
    }

    function applyFilters() {
        const searchTerm = document.getElementById('search').value.toLowerCase();
        const dateFrom = document.getElementById('date-from').value;
        const dateTo = document.getElementById('date-to').value;

        let filtered = allRepayments.filter(repayment => {
            // Search filter
            const matchesSearch = !searchTerm ||
                repayment.loan?.member?.full_name.toLowerCase().includes(searchTerm) ||
                repayment.loan?.loan_id.toLowerCase().includes(searchTerm) ||
                repayment.transaction_id.toLowerCase().includes(searchTerm);

            // Date range filter
            const repaymentDate = new Date(repayment.repayment_date);
            const matchesDateFrom = !dateFrom || repaymentDate >= new Date(dateFrom);
            const matchesDateTo = !dateTo || repaymentDate <= new Date(dateTo);

            return matchesSearch && matchesDateFrom && matchesDateTo;
        });

        displayRepayments(filtered);
    }

    function resetFilters() {
        document.getElementById('search').value = '';
        document.getElementById('date-from').value = '';
        document.getElementById('date-to').value = '';
        displayRepayments(allRepayments);
    }

    function viewRepayment(id) {
        const repayment = allRepayments.find(r => r.id === id);
        if (!repayment) return;

        alert(`Repayment Details:\n\nTransaction ID: ${repayment.transaction_id}\nAmount: TZS ${parseFloat(repayment.amount).toLocaleString()}\nDate: ${new Date(repayment.repayment_date).toLocaleDateString()}\nPayment Method: ${repayment.payment_method || 'N/A'}\nNotes: ${repayment.notes || 'None'}`);
    }

    async function deleteRepayment(id) {
        if (!confirm('Are you sure you want to delete this repayment? This will affect the loan balance.')) {
            return;
        }

        try {
            const response = await fetch(`/api/repayments/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                alert('Repayment deleted successfully');
                loadRepayments();
            } else {
                const data = await response.json();
                alert(data.message || 'Error deleting repayment');
            }
        } catch (error) {
            console.error('Error deleting repayment:', error);
            alert('Error deleting repayment');
        }
    }

    // Event listeners
    document.getElementById('search').addEventListener('input', applyFilters);
    document.getElementById('date-from').addEventListener('change', applyFilters);
    document.getElementById('date-to').addEventListener('change', applyFilters);

    document.addEventListener('DOMContentLoaded', loadRepayments);
</script>
@endsection
