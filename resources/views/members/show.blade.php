@extends('layouts.app')

@section('title', 'Member Details - Kikundi Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ url('/members') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-2">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Members
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white" id="member-name">Loading...</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" id="member-id"></p>
        </div>
        <div class="flex gap-3">
            <a href="#" id="edit-link" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                Edit Member
            </a>
            <button onclick="checkEligibility()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                Check Loan Eligibility
            </button>
        </div>
    </div>

    <!-- Member Info Cards -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Basic Info -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white" id="phone">-</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Type</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white" id="type">-</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1" id="status-badge">-</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date Joined</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white" id="date-joined">-</dd>
                </div>
            </dl>
        </div>

        <!-- Financial Summary -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Financial Summary</h2>
            <dl class="space-y-3">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Investments</dt>
                    <dd class="mt-1 text-lg font-semibold text-green-600 dark:text-green-400" id="total-investments">TZS 0</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Loans</dt>
                    <dd class="mt-1 text-lg font-semibold text-yellow-600 dark:text-yellow-400" id="active-loans">0</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Outstanding Balance</dt>
                    <dd class="mt-1 text-lg font-semibold text-red-600 dark:text-red-400" id="outstanding">TZS 0</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Max Loan Eligible</dt>
                    <dd class="mt-1 text-lg font-semibold text-indigo-600 dark:text-indigo-400" id="max-eligible">TZS 0</dd>
                </div>
            </dl>
        </div>

        <!-- Notes -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Notes</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400" id="notes">-</p>
        </div>
    </div>

    <!-- Tabbed Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px" role="tablist">
                <button onclick="switchTab('investments')" id="tab-investments"
                    class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400"
                    role="tab" aria-selected="true">
                    Investments
                </button>
                <button onclick="switchTab('loans')" id="tab-loans"
                    class="tab-btn px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
                    role="tab" aria-selected="false">
                    Loans
                </button>
            </nav>
        </div>

        <!-- Investments Tab Content -->
        <div id="panel-investments" class="tab-panel p-6" role="tabpanel">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Quarter</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="investments-tbody">
                        <tr><td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Loans Tab Content -->
        <div id="panel-loans" class="tab-panel p-6 hidden" role="tabpanel">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Loan Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Amount</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Due Date</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Balance</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="loans-tbody">
                        <tr><td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const memberId = window.location.pathname.split('/').pop();

    function switchTab(tab) {
        // Hide all panels and deactivate all tabs
        document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
            btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
            btn.setAttribute('aria-selected', 'false');
        });

        // Show selected panel and activate tab
        document.getElementById(`panel-${tab}`).classList.remove('hidden');
        const activeTab = document.getElementById(`tab-${tab}`);
        activeTab.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
        activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300', 'dark:text-gray-400', 'dark:hover:text-gray-300');
        activeTab.setAttribute('aria-selected', 'true');
    }

    async function loadMemberDetails() {
        try {
            const response = await fetch(`/api/members/${memberId}`);
            const result = await response.json();
            const member = result.data;

            document.getElementById('member-name').textContent = member.full_name;
            document.getElementById('member-id').textContent = `Member ID: ${member.member_id}`;
            document.getElementById('phone').textContent = member.phone_number || '-';
            document.getElementById('type').textContent = member.member_type;
            document.getElementById('date-joined').textContent = new Date(member.date_joined).toLocaleDateString();
            document.getElementById('notes').textContent = member.notes || 'No notes available';
            document.getElementById('edit-link').href = `/members/${memberId}/edit`;

            // Status badge
            const statusBadge = document.getElementById('status-badge');
            statusBadge.innerHTML = `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                member.status === 'Active'
                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
            }">${member.status}</span>`;

            // Financial summary from computed fields
            const totalInvestment = parseFloat(member.total_investment || 0);
            const maxLoanEligible = parseFloat(member.max_loan_eligible || 0);
            const totalOutstanding = parseFloat(member.total_outstanding || 0);

            document.getElementById('total-investments').textContent = `TZS ${totalInvestment.toLocaleString()}`;
            document.getElementById('max-eligible').textContent = `TZS ${maxLoanEligible.toLocaleString()}`;
            document.getElementById('outstanding').textContent = `TZS ${totalOutstanding.toLocaleString()}`;

            // Active loans count
            const activeLoans = member.active_loans || [];
            document.getElementById('active-loans').textContent = activeLoans.length;

            // Render investments from included relationship
            renderInvestments(member.investments || []);

            // Render loans from included relationship
            renderLoans(member.loans || []);

        } catch (error) {
            console.error('Error loading member:', error);
        }
    }

    function renderInvestments(investments) {
        const tbody = document.getElementById('investments-tbody');
        if (investments.length > 0) {
            tbody.innerHTML = investments.map(inv => `
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${new Date(inv.investment_date).toLocaleDateString()}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-green-600 dark:text-green-400">TZS ${parseFloat(inv.amount).toLocaleString()}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${inv.quarter} ${inv.year}</td>
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">${inv.notes || '-'}</td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No investments yet</td></tr>';
        }
    }

    function renderLoans(loans) {
        const tbody = document.getElementById('loans-tbody');
        if (loans.length > 0) {
            tbody.innerHTML = loans.map(loan => {
                const totalRepaid = loan.repayments ? loan.repayments.reduce((sum, r) => sum + parseFloat(r.amount || 0), 0) : 0;
                const remaining = parseFloat(loan.total_to_repay || loan.loan_amount) - totalRepaid;
                return `
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${new Date(loan.loan_date).toLocaleDateString()}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-white">TZS ${parseFloat(loan.loan_amount).toLocaleString()}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${new Date(loan.due_date).toLocaleDateString()}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                                loan.status === 'Active' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                loan.status === 'Completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                            }">${loan.status}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">TZS ${remaining.toLocaleString()}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="/loans/${loan.id}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">View</a>
                        </td>
                    </tr>
                `;
            }).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-3 text-center text-gray-500 dark:text-gray-400">No loans yet</td></tr>';
        }
    }

    async function checkEligibility() {
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

            let status = data.can_borrow ? 'Eligible' : 'Not Eligible';
            let details = `Loan Eligibility:\n\nTotal Investments: TZS ${parseFloat(data.total_investment).toLocaleString()}\nMax Eligible Loan: TZS ${parseFloat(data.max_loan_eligible).toLocaleString()}\nCurrent Outstanding: TZS ${parseFloat(data.current_outstanding).toLocaleString()}\nAvailable to Borrow: TZS ${parseFloat(data.available_to_borrow).toLocaleString()}`;

            if (data.has_active_loan) {
                details += '\n\nNote: Member has an active loan that must be fully repaid before taking a new loan.';
            }

            details += `\n\nStatus: ${status}\n${data.message}`;

            alert(details);
        } catch (error) {
            console.error('Error checking eligibility:', error);
            alert('Error checking loan eligibility');
        }
    }

    document.addEventListener('DOMContentLoaded', loadMemberDetails);
</script>
@endsection
