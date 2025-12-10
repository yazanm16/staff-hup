<!-- attendance/report.blade.php -->
@extends('layouts.app')

@section('title', 'Attendance Report')
@section('page-title', 'Attendance Report')

@section('content')
    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Filter Report</h3>
            <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                    <select class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Employees</option>
                        <option value="1">John Doe</option>
                        <option value="2">Jane Smith</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Departments</option>
                        <option value="engineering">Engineering</option>
                        <option value="marketing">Marketing</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-4 flex justify-end space-x-3 mt-2">
                    <button type="reset" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                        Reset
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Generate Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Present Days</p>
                        <p class="text-3xl font-bold mt-2">18</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Absent Days</p>
                        <p class="text-3xl font-bold mt-2">2</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Late Arrivals</p>
                        <p class="text-3xl font-bold mt-2">3</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Avg. Hours/Day</p>
                        <p class="text-3xl font-bold mt-2">7.8</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-business-time text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Report -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Attendance Details</h3>
                <button class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export CSV
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check
                                In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check
                                Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Working Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @for ($i = 0; $i < 10; $i++)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                        <span>John Doe</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">Dec {{ 10 + $i }}, 2023</td>
                                <td class="px-6 py-4">9:0{{ $i }} AM</td>
                                <td class="px-6 py-4">5:3{{ $i }} PM</td>
                                <td class="px-6 py-4">8.5 hours</td>
                                <td class="px-6 py-4">
                                    @if ($i % 5 == 0)
                                        <span
                                            class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Late</span>
                                    @elseif($i % 5 == 1)
                                        <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">Absent</span>
                                    @else
                                        <span
                                            class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Present</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-500">
                                        {{ $i % 5 == 0 ? 'Late arrival' : ($i % 5 == 1 ? 'Sick leave' : 'Normal') }}
                                    </span>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Showing 1 to 10 of 150 records
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
