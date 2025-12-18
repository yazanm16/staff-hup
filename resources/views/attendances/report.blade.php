<!-- attendance/report.blade.php -->
@extends('layouts.app')

@section('title', 'Attendance Report')
@section('page-title', 'Attendance Report')

@section('content')
    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Filter Report</h3>
            <form class="grid grid-cols-1 md:grid-cols-4 gap-4" method="GET">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                    <select name="user_id"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Employee</option>
                        @if (count($users) > 0)
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <select name="department_id"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('department_id') }}">
                        <option value="">Select Department</option>
                        @if (count($departments) > 0)
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="from" value="{{ request('from', $from) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="to" value="{{ request('to', $to) }}"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-4 flex justify-end space-x-3 mt-2">
                    <a href="{{ route('attendances.reports') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                        Reset
                    </a>
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
                        <p class="text-3xl font-bold mt-2">{{ $presentDays }}</p>
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
                        <p class="text-3xl font-bold mt-2">{{ $absentDays }}</p>
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
                        <p class="text-3xl font-bold mt-2">{{ $lateArrivals }}</p>
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
                        <p class="text-3xl font-bold mt-2">{{ $avgHours }}</p>
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
                <a href="{{ route('attendances.generateReport', request()->query()) }}"
                    class="text-blue-600 hover:text-blue-800 flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export CSV
                </a>
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
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($attendances as $attendance)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                            @if (!empty($attendance->User->image) && file_exists(public_path('storage/employees/' . $attendance->User->image)))
                                                <img class="w-full h-full object-cover"
                                                    src="{{ asset('storage/employees/' . $attendance->User->image) }}"
                                                    alt="User Image">
                                            @else
                                                <i class="fas fa-user text-blue-600"></i>
                                            @endif
                                        </div>
                                        <span>{{ $attendance->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    {{ $attendance->check_in ? $attendance->check_in->format('h:i A') : '-' }}</td>
                                <td class="px-6 py-4">
                                    {{ $attendance->check_out ? $attendance->check_out->format('h:i A') : '-' }}</td>
                                <td class="px-6 py-4">{{ $attendance->work_hours }}</td>
                                <td class="px-6 py-4">
                                    @if ($attendance->check_in && !$attendance->check_out)
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Working</span>
                                    @elseif($attendance->check_out)
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Present</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Absent</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t flex justify-between items-center">
                {{ $attendances->links('vendor.pagination.tailwind') }}

            </div>
        </div>
    </div>
@endsection
