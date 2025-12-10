<!-- employees/index.blade.php -->
@extends('layouts.app')

@section('title', 'Employees')
@section('page-title', 'Employees Management')

@section('content')
    <div class="space-y-6">
        <!-- Header with Add Button -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Employees</h2>
                <p class="text-gray-600">Manage your team members</p>
            </div>
            <a href="{{ route('employees.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add Employee
            </a>
        </div>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" placeholder="Search employees..."
                            class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex gap-2">
                    <select class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>All Departments</option>
                        <option>Engineering</option>
                        <option>Marketing</option>
                        <option>Sales</option>
                    </select>
                    <select class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Employees Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @for ($i = 0; $i < 8; $i++)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium">John Doe</p>
                                            <p class="text-sm text-gray-500">john@example.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Engineering</span>
                                </td>
                                <td class="px-6 py-4">Senior Developer</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('employees.edit', 1) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <a href="#" class="text-gray-600 hover:text-gray-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Showing 1 to 8 of 142 results
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border rounded hover:bg-gray-50">&laquo;</button>
                    <button class="px-3 py-1 border rounded bg-blue-600 text-white">1</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-50">2</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-50">3</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-50">&raquo;</button>
                </div>
            </div>
        </div>
    </div>
@endsection
