@extends('layouts.app')

@section('title', 'Members - Kikundi Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Members</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your Kikundi members</p>
        </div>
        <a href="{{ url('/members/create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Add Member
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Members</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="active-members">-</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Students</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="student-members">-</div>
        </div>
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Workers</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" id="worker-members">-</div>
        </div>
    </div>

    <!-- Members Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Members</h2>
                <input type="search" id="search" placeholder="Search members..." class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Member ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date Joined</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="members-tbody">
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let allMembers = [];

    async function loadMembers() {
        try {
            const response = await fetch('/api/members');
            const data = await response.json();
            allMembers = data.data || [];

            displayMembers(allMembers);
        } catch (error) {
            console.error('Error loading members:', error);
            document.getElementById('members-tbody').innerHTML = `
                <tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Error loading members</td></tr>
            `;
        }
    }

    async function loadStatistics() {
        try {
            const response = await fetch('/api/members/statistics');
            const result = await response.json();
            const stats = result.data;

            document.getElementById('active-members').textContent = stats.active_members || 0;
            document.getElementById('student-members').textContent = stats.students || 0;
            document.getElementById('worker-members').textContent = stats.workers || 0;
        } catch (error) {
            console.error('Error loading statistics:', error);
        }
    }

    function displayMembers(members) {
        const tbody = document.getElementById('members-tbody');

        if (members.length === 0) {
            tbody.innerHTML = `
                <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No members found</td></tr>
            `;
            return;
        }

        tbody.innerHTML = members.map(member => `
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                    ${member.member_id}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    ${member.full_name}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${member.phone_number || '-'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${member.member_type}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                        member.status === 'Active'
                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                            : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                    }">
                        ${member.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${new Date(member.date_joined).toLocaleDateString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="/members/${member.id}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">View</a>
                    <a href="/members/${member.id}/edit" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 mr-3">Edit</a>
                    <button onclick="deleteMember(${member.id})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                </td>
            </tr>
        `).join('');
    }

    // Search functionality
    document.getElementById('search').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const filtered = allMembers.filter(member =>
            member.full_name.toLowerCase().includes(searchTerm) ||
            member.member_id.toLowerCase().includes(searchTerm) ||
            (member.phone_number && member.phone_number.includes(searchTerm))
        );
        displayMembers(filtered);
    });

    async function deleteMember(id) {
        if (!confirm('Are you sure you want to delete this member?')) {
            return;
        }

        try {
            const response = await fetch(`/api/members/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                alert('Member deleted successfully');
                loadMembers();
                loadStatistics();
            } else {
                alert('Error deleting member');
            }
        } catch (error) {
            console.error('Error deleting member:', error);
            alert('Error deleting member');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadMembers();
        loadStatistics();
    });
</script>
@endsection
