@extends('layouts.app')

@section('title', 'Dashboard - Kikundi Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Overview of your Kikundi's performance</p>
        </div>
        <div class="text-sm text-gray-500">
            {{ now()->format('l, F j, Y') }}
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Members -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 dark:bg-indigo-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Members</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white" id="total-members">-</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Investments -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Investments</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white" id="total-investments">-</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Loans -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Active Loans</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white" id="active-loans">-</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outstanding Amount -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 dark:bg-red-900 rounded-md p-3">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Outstanding</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white" id="outstanding-amount">-</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Recent Investments -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Investments</h2>
            </div>
            <div class="p-6">
                <div class="flow-root">
                    <ul role="list" class="-mb-8" id="recent-investments">
                        <li class="text-center py-4 text-gray-500 dark:text-gray-400">Loading...</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Recent Loans -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Loans</h2>
            </div>
            <div class="p-6">
                <div class="flow-root">
                    <ul role="list" class="-mb-8" id="recent-loans">
                        <li class="text-center py-4 text-gray-500 dark:text-gray-400">Loading...</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Overdue Loans Alert -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Overdue Loans</h2>
        </div>
        <div class="p-6">
            <div id="overdue-loans">
                <p class="text-center py-4 text-gray-500 dark:text-gray-400">Loading...</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Fetch dashboard data
    async function loadDashboard() {
        try {
            const response = await fetch('/api/reports/dashboard', {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            const data = result.data;

            // Update stats
            document.getElementById('total-members').textContent = data.members?.total || 0;
            document.getElementById('total-investments').textContent = `TZS ${(data.investments?.total_amount || 0).toLocaleString()}`;
            document.getElementById('active-loans').textContent = data.loans?.active_loans || 0;
            document.getElementById('outstanding-amount').textContent = `TZS ${(data.financials?.outstanding_balance || 0).toLocaleString()}`;

        } catch (error) {
            console.error('Error loading dashboard:', error);
            document.getElementById('total-members').textContent = 'Error';
            document.getElementById('total-investments').textContent = 'Error';
            document.getElementById('active-loans').textContent = 'Error';
            document.getElementById('outstanding-amount').textContent = 'Error';
        }
    }

    // Fetch recent investments
    async function loadRecentInvestments() {
        try {
            const response = await fetch('/api/investments?limit=5', {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            const container = document.getElementById('recent-investments');
            if (data.data && data.data.length > 0) {
                container.innerHTML = data.data.map(investment => `
                    <li class="pb-4 mb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">${investment.member?.full_name || 'N/A'}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">${new Date(investment.investment_date).toLocaleDateString()}</p>
                            </div>
                            <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                TZS ${parseFloat(investment.amount).toLocaleString()}
                            </div>
                        </div>
                    </li>
                `).join('');
            } else {
                container.innerHTML = '<li class="text-center py-4 text-gray-500 dark:text-gray-400">No recent investments</li>';
            }
        } catch (error) {
            console.error('Error loading investments:', error);
        }
    }

    // Fetch recent loans
    async function loadRecentLoans() {
        try {
            const response = await fetch('/api/loans?limit=5', {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            const container = document.getElementById('recent-loans');
            if (data.data && data.data.length > 0) {
                container.innerHTML = data.data.map(loan => `
                    <li class="pb-4 mb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                        <div class="flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">${loan.member?.full_name || 'N/A'}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">${new Date(loan.loan_date).toLocaleDateString()}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    TZS ${parseFloat(loan.loan_amount).toLocaleString()}
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${
                                    loan.status === 'Active' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                    loan.status === 'Completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                }">
                                    ${loan.status}
                                </span>
                            </div>
                        </div>
                    </li>
                `).join('');
            } else {
                container.innerHTML = '<li class="text-center py-4 text-gray-500 dark:text-gray-400">No recent loans</li>';
            }
        } catch (error) {
            console.error('Error loading loans:', error);
        }
    }

    // Fetch overdue loans
    async function loadOverdueLoans() {
        try {
            const response = await fetch('/api/loans/overdue', {
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            const container = document.getElementById('overdue-loans');
            if (data.data && data.data.length > 0) {
                container.innerHTML = `
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-4">
                        <p class="text-sm text-red-800 dark:text-red-200 font-medium">
                            ${data.data.length} loan(s) are overdue
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Member</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Due Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Days Overdue</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                ${data.data.map(loan => {
                                    const dueDate = new Date(loan.due_date);
                                    const today = new Date();
                                    const daysOverdue = Math.floor((today - dueDate) / (1000 * 60 * 60 * 24));

                                    return `
                                        <tr>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${loan.member?.full_name || 'N/A'}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">TZS ${parseFloat(loan.loan_amount).toLocaleString()}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${dueDate.toLocaleDateString()}</td>
                                            <td class="px-4 py-3 text-sm text-red-600 dark:text-red-400 font-medium">${daysOverdue} days</td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                container.innerHTML = '<p class="text-center py-4 text-green-600 dark:text-green-400">No overdue loans</p>';
            }
        } catch (error) {
            console.error('Error loading overdue loans:', error);
            container.innerHTML = '<p class="text-center py-4 text-gray-500 dark:text-gray-400">Error loading overdue loans</p>';
        }
    }

    // Load all data when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadDashboard();
        loadRecentInvestments();
        loadRecentLoans();
        loadOverdueLoans();
    });
</script>
@endsection
