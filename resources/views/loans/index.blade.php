@extends('layouts.app')

@section('title', 'Loans - Kikundi Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Loans</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage member loans</p>
        </div>
        <a href="{{ url('/loans/create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            New Loan
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Loans</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="active-loans">0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Disbursed</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="total-disbursed">TZS 0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Outstanding</div>
            <div class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-400" id="outstanding">TZS 0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Overdue Loans</div>
            <div class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-400" id="overdue-count">0</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <input type="search" id="search" placeholder="Search by member name..." class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
            <select id="status-filter" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Completed">Completed</option>
                <option value="Defaulted">Defaulted</option>
            </select>
            <button onclick="resetFilters()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                Reset Filters
            </button>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Loans</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loan ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Disbursed</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loan Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="loans-tbody">
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let allLoans = [];

    async function loadLoans() {
        try {
            const response = await fetch('/api/loans');
            const data = await response.json();
            allLoans = data.data || [];
            displayLoans(allLoans);
        } catch (error) {
            console.error('Error loading loans:', error);
        }
    }

    async function loadStatistics() {
        try {
            const response = await fetch('/api/loans/statistics');
            const result = await response.json();
            const stats = result.data;

            document.getElementById('active-loans').textContent = stats.active_loans || 0;
            document.getElementById('total-disbursed').textContent = `TZS ${(stats.total_disbursed || 0).toLocaleString()}`;
            document.getElementById('outstanding').textContent = `TZS ${(stats.outstanding_balance || 0).toLocaleString()}`;

            // Load overdue count
            const overdueResponse = await fetch('/api/loans/overdue');
            const overdueResult = await overdueResponse.json();
            document.getElementById('overdue-count').textContent = overdueResult.data?.length || 0;
        } catch (error) {
            console.error('Error loading statistics:', error);
        }
    }

    function displayLoans(loans) {
        const tbody = document.getElementById('loans-tbody');

        if (loans.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No loans found</td></tr>';
            return;
        }

        tbody.innerHTML = loans.map(loan => {
            const remaining = parseFloat(loan.total_to_repay) - (parseFloat(loan.total_repaid) || 0);
            const isOverdue = loan.status === 'Active' && new Date(loan.due_date) < new Date();

            return `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 ${isOverdue ? 'bg-red-50 dark:bg-red-900/20' : ''}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                        ${loan.loan_id}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        <a href="/members/${loan.member_id}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                            ${loan.member?.full_name || 'N/A'}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                        TZS ${parseFloat(loan.loan_amount).toLocaleString()}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        TZS ${parseFloat(loan.net_disbursed).toLocaleString()}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        ${new Date(loan.loan_date).toLocaleDateString()}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm ${isOverdue ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-500 dark:text-gray-400'}">
                        ${new Date(loan.due_date).toLocaleDateString()}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                            loan.status === 'Active' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                            loan.status === 'Completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                        }">
                            ${loan.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold ${remaining > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'}">
                        TZS ${remaining.toLocaleString()}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="/loans/${loan.id}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">View</a>
                        <button onclick="deleteLoan(${loan.id})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function applyFilters() {
        const searchTerm = document.getElementById('search').value.toLowerCase();
        const status = document.getElementById('status-filter').value;

        let filtered = allLoans.filter(loan => {
            const matchesSearch = !searchTerm || loan.member?.full_name.toLowerCase().includes(searchTerm);
            const matchesStatus = !status || loan.status === status;
            return matchesSearch && matchesStatus;
        });

        displayLoans(filtered);
    }

    function resetFilters() {
        document.getElementById('search').value = '';
        document.getElementById('status-filter').value = '';
        displayLoans(allLoans);
    }

    async function deleteLoan(id) {
        if (!confirm('Are you sure you want to delete this loan?')) {
            return;
        }

        try {
            const response = await fetch(`/api/loans/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                alert('Loan deleted successfully');
                loadLoans();
                loadStatistics();
            } else {
                alert('Error deleting loan');
            }
        } catch (error) {
            console.error('Error deleting loan:', error);
            alert('Error deleting loan');
        }
    }

    document.getElementById('search').addEventListener('input', applyFilters);
    document.getElementById('status-filter').addEventListener('change', applyFilters);

    document.addEventListener('DOMContentLoaded', function() {
        loadLoans();
        loadStatistics();
    });
</script>
@endsection
