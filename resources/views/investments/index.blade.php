@extends('layouts.app')

@section('title', 'Investments - Kikundi Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Investments</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track member investments</p>
        </div>
        <a href="{{ url('/investments/create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Record Investment
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Investments</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="total-investments">TZS 0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">This Quarter</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="quarter-investments">TZS 0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">This Year</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="year-investments">TZS 0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Investors</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="total-investors">0</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <input type="search" id="search" placeholder="Search by member name..." class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
            <select id="quarter-filter" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                <option value="">All Quarters</option>
                <option value="Q1">Q1</option>
                <option value="Q2">Q2</option>
                <option value="Q3">Q3</option>
                <option value="Q4">Q4</option>
            </select>
            <select id="year-filter" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                <option value="">All Years</option>
            </select>
            <button onclick="resetFilters()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                Reset Filters
            </button>
        </div>
    </div>

    <!-- Investments Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Investment Records</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quarter</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Notes</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="investments-tbody">
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let allInvestments = [];
    const currentYear = new Date().getFullYear();

    // Populate year filter
    const yearFilter = document.getElementById('year-filter');
    for (let year = currentYear; year >= currentYear - 5; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearFilter.appendChild(option);
    }

    async function loadInvestments() {
        try {
            const response = await fetch('/api/investments');
            const data = await response.json();
            allInvestments = data.data || [];
            displayInvestments(allInvestments);
        } catch (error) {
            console.error('Error loading investments:', error);
        }
    }

    async function loadStatistics() {
        try {
            const response = await fetch('/api/investments/statistics');
            const result = await response.json();
            const stats = result.data;

            document.getElementById('total-investments').textContent = `TZS ${(stats.total_investments || 0).toLocaleString()}`;
            document.getElementById('total-investors').textContent = stats.count || 0;

            // Calculate current quarter and year totals from by_quarter data
            const now = new Date();
            const currentQuarter = `Q${Math.ceil((now.getMonth() + 1) / 3)}`;
            const quarterData = stats.by_quarter || [];
            const currentQuarterTotal = quarterData.find(q => q.quarter === currentQuarter)?.total || 0;
            document.getElementById('quarter-investments').textContent = `TZS ${parseFloat(currentQuarterTotal).toLocaleString()}`;

            // Fetch year-specific stats
            const yearResponse = await fetch(`/api/investments/statistics?year=${now.getFullYear()}`);
            const yearResult = await yearResponse.json();
            document.getElementById('year-investments').textContent = `TZS ${(yearResult.data.total_investments || 0).toLocaleString()}`;
        } catch (error) {
            console.error('Error loading statistics:', error);
        }
    }

    function displayInvestments(investments) {
        const tbody = document.getElementById('investments-tbody');

        if (investments.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No investments found</td></tr>';
            return;
        }

        tbody.innerHTML = investments.map(inv => `
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                    ${inv.transaction_id}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    <a href="/members/${inv.member_id}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">
                        ${inv.member?.full_name || 'N/A'}
                    </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                    TZS ${parseFloat(inv.amount).toLocaleString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${new Date(inv.investment_date).toLocaleDateString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${inv.quarter} ${inv.year}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                    ${inv.notes || '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="deleteInvestment(${inv.id})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    function applyFilters() {
        const searchTerm = document.getElementById('search').value.toLowerCase();
        const quarter = document.getElementById('quarter-filter').value;
        const year = document.getElementById('year-filter').value;

        let filtered = allInvestments.filter(inv => {
            const matchesSearch = !searchTerm || inv.member?.full_name.toLowerCase().includes(searchTerm);
            const matchesQuarter = !quarter || inv.quarter === quarter;
            const matchesYear = !year || inv.year.toString() === year;

            return matchesSearch && matchesQuarter && matchesYear;
        });

        displayInvestments(filtered);
    }

    function resetFilters() {
        document.getElementById('search').value = '';
        document.getElementById('quarter-filter').value = '';
        document.getElementById('year-filter').value = '';
        displayInvestments(allInvestments);
    }

    async function deleteInvestment(id) {
        if (!confirm('Are you sure you want to delete this investment record?')) {
            return;
        }

        try {
            const response = await fetch(`/api/investments/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                alert('Investment deleted successfully');
                loadInvestments();
                loadStatistics();
            } else {
                alert('Error deleting investment');
            }
        } catch (error) {
            console.error('Error deleting investment:', error);
            alert('Error deleting investment');
        }
    }

    // Event listeners
    document.getElementById('search').addEventListener('input', applyFilters);
    document.getElementById('quarter-filter').addEventListener('change', applyFilters);
    document.getElementById('year-filter').addEventListener('change', applyFilters);

    document.addEventListener('DOMContentLoaded', function() {
        loadInvestments();
        loadStatistics();
    });
</script>
@endsection
