@extends('layouts.app')

@section('title', 'Reports - Kikundi Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reports</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Generate and view financial reports</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Members</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="total-members">0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Investments</div>
            <div class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400" id="total-investments">TZS 0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Loans</div>
            <div class="mt-1 text-2xl font-semibold text-yellow-600 dark:text-yellow-400" id="active-loans">0</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Outstanding Amount</div>
            <div class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-400" id="outstanding">TZS 0</div>
        </div>
    </div>

    <!-- Financial Reports -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Financial Reports</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Generate period-based financial reports</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Quarterly Report -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors">
                    <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg mb-4">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Quarterly Report</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Generate a report for a specific quarter</p>

                    <div class="space-y-3">
                        <select id="quarter-select" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="1">Q1 (Jan-Mar)</option>
                            <option value="2">Q2 (Apr-Jun)</option>
                            <option value="3">Q3 (Jul-Sep)</option>
                            <option value="4">Q4 (Oct-Dec)</option>
                        </select>
                        <select id="quarter-year" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </select>
                        <button onclick="generateQuarterlyReport()" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg">
                            Generate Report
                        </button>
                    </div>
                </div>

                <!-- Biannual Report -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors">
                    <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg mb-4">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Biannual Report</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Generate a half-year report</p>

                    <div class="space-y-3">
                        <select id="biannual-select" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="1">H1 (Jan-Jun)</option>
                            <option value="2">H2 (Jul-Dec)</option>
                        </select>
                        <select id="biannual-year" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </select>
                        <button onclick="generateBiannualReport()" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg">
                            Generate Report
                        </button>
                    </div>
                </div>

                <!-- Annual Report -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors">
                    <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg mb-4">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Annual Report</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Generate a full year report</p>

                    <div class="space-y-3">
                        <select id="annual-year" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </select>
                        <button onclick="generateAnnualReport()" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg">
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Member Statements -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Member Statements</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Generate detailed statements for individual members</p>
        </div>
        <div class="p-6">
            <div class="max-w-xl">
                <div class="space-y-4">
                    <div>
                        <label for="member-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Member
                        </label>
                        <select id="member-select" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Choose a member</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="statement-from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                From Date
                            </label>
                            <input type="date" id="statement-from" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="statement-to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                To Date
                            </label>
                            <input type="date" id="statement-to" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>

                    <button onclick="generateMemberStatement()" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                        Generate Statement
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Display Area -->
    <div id="report-display" class="hidden bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white" id="report-title">Report</h2>
            <button onclick="closeReport()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6" id="report-content">
            <!-- Report content will be inserted here -->
        </div>
    </div>
</div>

<script>
    // Populate year dropdowns
    function populateYears() {
        const currentYear = new Date().getFullYear();
        const years = [];
        for (let i = 0; i < 5; i++) {
            years.push(currentYear - i);
        }

        const yearSelects = ['quarter-year', 'biannual-year', 'annual-year'];
        yearSelects.forEach(selectId => {
            const select = document.getElementById(selectId);
            years.forEach(year => {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                select.appendChild(option);
            });
        });

        // Set default quarter based on current date
        const currentQuarter = Math.floor((new Date().getMonth() / 3)) + 1;
        document.getElementById('quarter-select').value = currentQuarter;
    }

    // Load members for statement
    async function loadMembers() {
        try {
            const response = await fetch('/api/members');
            const data = await response.json();

            const select = document.getElementById('member-select');
            (data.data || []).forEach(member => {
                const option = document.createElement('option');
                option.value = member.id;
                option.textContent = `${member.full_name} (${member.member_id})`;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading members:', error);
        }
    }

    // Load quick stats
    async function loadStats() {
        try {
            // Load member stats
            const memberResponse = await fetch('/api/members/statistics');
            const memberData = await memberResponse.json();
            document.getElementById('total-members').textContent = memberData.total || 0;

            // Load investment stats
            const investmentResponse = await fetch('/api/investments');
            const investmentData = await investmentResponse.json();
            const totalInvestments = (investmentData.data || []).reduce((sum, inv) => sum + parseFloat(inv.amount), 0);
            document.getElementById('total-investments').textContent = `TZS ${totalInvestments.toLocaleString('en-US', { minimumFractionDigits: 2 })}`;

            // Load loan stats
            const loanResponse = await fetch('/api/loans/statistics');
            const loanData = await loanResponse.json();
            document.getElementById('active-loans').textContent = loanData.active_count || 0;
            document.getElementById('outstanding').textContent = `TZS ${(loanData.outstanding || 0).toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    // Generate quarterly report
    async function generateQuarterlyReport() {
        const quarter = document.getElementById('quarter-select').value;
        const year = document.getElementById('quarter-year').value;

        try {
            const response = await fetch(`/api/reports/quarterly?quarter=${quarter}&year=${year}`);
            const data = await response.json();

            displayReport(`Q${quarter} ${year} Report`, formatQuarterlyReport(data.data));
        } catch (error) {
            alert('Error generating quarterly report');
            console.error(error);
        }
    }

    // Generate biannual report
    async function generateBiannualReport() {
        const half = document.getElementById('biannual-select').value;
        const year = document.getElementById('biannual-year').value;

        try {
            const response = await fetch(`/api/reports/biannual?half=${half}&year=${year}`);
            const data = await response.json();

            displayReport(`H${half} ${year} Report`, formatBiannualReport(data.data));
        } catch (error) {
            alert('Error generating biannual report');
            console.error(error);
        }
    }

    // Generate annual report
    async function generateAnnualReport() {
        const year = document.getElementById('annual-year').value;

        try {
            const response = await fetch(`/api/reports/annual?year=${year}`);
            const data = await response.json();

            displayReport(`${year} Annual Report`, formatAnnualReport(data.data));
        } catch (error) {
            alert('Error generating annual report');
            console.error(error);
        }
    }

    // Generate member statement
    async function generateMemberStatement() {
        const memberId = document.getElementById('member-select').value;
        const fromDate = document.getElementById('statement-from').value;
        const toDate = document.getElementById('statement-to').value;

        if (!memberId) {
            alert('Please select a member');
            return;
        }

        try {
            let url = `/api/reports/member/${memberId}`;
            const params = new URLSearchParams();
            if (fromDate) params.append('from', fromDate);
            if (toDate) params.append('to', toDate);
            if (params.toString()) url += `?${params.toString()}`;

            const response = await fetch(url);
            const data = await response.json();

            const memberName = document.getElementById('member-select').selectedOptions[0].text;
            displayReport(`Member Statement - ${memberName}`, formatMemberStatement(data.data));
        } catch (error) {
            alert('Error generating member statement');
            console.error(error);
        }
    }

    // Format report data
    function formatQuarterlyReport(data) {
        return `
            <div class="space-y-6">
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Investments</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">TZS ${parseFloat(data.total_investments || 0).toLocaleString()}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Loans</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">TZS ${parseFloat(data.total_loans || 0).toLocaleString()}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Repayments</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">TZS ${parseFloat(data.total_repayments || 0).toLocaleString()}</div>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Report generated on ${new Date().toLocaleDateString()}</p>
            </div>
        `;
    }

    function formatBiannualReport(data) {
        return formatQuarterlyReport(data);
    }

    function formatAnnualReport(data) {
        return formatQuarterlyReport(data);
    }

    function formatMemberStatement(data) {
        return `
            <div class="space-y-6">
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Investments</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">TZS ${parseFloat(data.total_investments || 0).toLocaleString()}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Borrowed</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">TZS ${parseFloat(data.total_loans || 0).toLocaleString()}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                        <div class="text-sm text-gray-500 dark:text-gray-400">Total Repaid</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">TZS ${parseFloat(data.total_repayments || 0).toLocaleString()}</div>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Statement generated on ${new Date().toLocaleDateString()}</p>
            </div>
        `;
    }

    // Display report
    function displayReport(title, content) {
        document.getElementById('report-title').textContent = title;
        document.getElementById('report-content').innerHTML = content;
        document.getElementById('report-display').classList.remove('hidden');
        document.getElementById('report-display').scrollIntoView({ behavior: 'smooth' });
    }

    // Close report
    function closeReport() {
        document.getElementById('report-display').classList.add('hidden');
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        populateYears();
        loadMembers();
        loadStats();

        // Set default date range (last 30 days)
        const today = new Date();
        const thirtyDaysAgo = new Date(today);
        thirtyDaysAgo.setDate(today.getDate() - 30);

        document.getElementById('statement-to').valueAsDate = today;
        document.getElementById('statement-from').valueAsDate = thirtyDaysAgo;
    });
</script>
@endsection
