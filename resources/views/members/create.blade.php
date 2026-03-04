@extends('layouts.app')

@section('title', 'Add Member - Kikundi Management')

@section('content')
    <div class="max-w-3xl mx-auto">
        <!-- Page Header -->
        <div class="mb-6">
            <a href="{{ url('/members') }}"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 mb-4">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Members
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add New Member</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Fill in the details to register a new member</p>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
            <form id="member-form" class="p-6 space-y-6">
                <!-- Member ID -->
                <div>
                    <label for="member_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Member ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="member_id" name="member_id" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                        placeholder="e.g., MEM001">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Unique identifier for the member</p>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="error-member_id"></p>
                </div>

                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="full_name" name="full_name" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Enter full name">
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="error-full_name"></p>
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="phone_number" name="phone_number" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                        placeholder="e.g., +255700000000">
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="error-phone_number"></p>
                </div>

                <!-- Member Type -->
                <div>
                    <label for="member_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Member Type <span class="text-red-500">*</span>
                    </label>
                    <select id="member_type" name="member_type" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        <option value="">Select type</option>
                        <option value="Student">Student</option>
                        <option value="Worker">Worker</option>
                    </select>
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="error-member_type"></p>
                </div>

                <!-- Date Joined -->
                <div>
                    <label for="date_joined" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Date Joined <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="date_joined" name="date_joined" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400 hidden" id="error-date_joined"></p>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Notes
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Any additional notes about the member"></textarea>
                </div>

                <!-- Error Message -->
                <div id="error-message" class="hidden p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg"></div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-3">
                    <a href="{{ url('/members') }}"
                        class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg">
                        Add Member
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Set default date to today
        document.getElementById('date_joined').valueAsDate = new Date();

        function clearFieldErrors() {
            const errorFields = document.querySelectorAll('[id^="error-"]');
            errorFields.forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            // Reset input borders
            const inputs = document.querySelectorAll('#member-form input, #member-form select');
            inputs.forEach(input => {
                input.classList.remove('border-red-500', 'dark:border-red-500');
                input.classList.add('border-gray-300', 'dark:border-gray-600');
            });
        }

        function showFieldErrors(errors) {
            Object.keys(errors).forEach(field => {
                const errorEl = document.getElementById(`error-${field}`);
                const inputEl = document.getElementById(field);
                if (errorEl) {
                    errorEl.textContent = errors[field][0];
                    errorEl.classList.remove('hidden');
                }
                if (inputEl) {
                    inputEl.classList.remove('border-gray-300', 'dark:border-gray-600');
                    inputEl.classList.add('border-red-500', 'dark:border-red-500');
                }
            });
        }

        document.getElementById('member-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const errorDiv = document.getElementById('error-message');
            errorDiv.classList.add('hidden');
            clearFieldErrors();

            const formData = {
                member_id: document.getElementById('member_id').value,
                full_name: document.getElementById('full_name').value,
                phone_number: document.getElementById('phone_number').value,
                member_type: document.getElementById('member_type').value,
                date_joined: document.getElementById('date_joined').value,
                status: document.getElementById('status').value,
                notes: document.getElementById('notes').value
            };

            try {
                const response = await fetch('/api/members', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Member added successfully!');
                    window.location.href = '/members';
                } else {
                    if (data.errors) {
                        showFieldErrors(data.errors);
                    }
                    errorDiv.textContent = data.message || 'Please fix the errors below and try again.';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('hidden');
            }
        });
    </script>
@endsection