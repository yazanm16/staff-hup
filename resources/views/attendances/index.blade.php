<!-- attendance/index.blade.php -->
@extends('layouts.app')

@section('title', 'Attendance')
@section('page-title', 'Attendance')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="space-y-6">
            <!-- Current Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-blue-600 text-3xl"></i>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-800 mb-2">
                        @if (isset($attendance) && $attendance->check_out === null)
                            Currently Working
                        @else
                            Not Checked In
                        @endif
                    </h3>

                    <p class="text-gray-600 mb-6">
                        {{ now()->format('l, F j, Y') }}
                    </p>

                    <div class="flex justify-center space-x-4">
                        @if (!isset($attendance) || $attendance->check_out !== null)
                            <form method="POST" action="{{ route('attendance.checkin') }}">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg flex items-center text-lg">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Check In
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('attendance.checkout') }}">
                                @csrf
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg flex items-center text-lg">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Check Out
                                </button>
                            </form>
                        @endif
                    </div>

                    @if (isset($attendance) && $attendance->check_out === null)
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg inline-block">
                            <p class="text-blue-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                Checked in at {{ \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- This Week's Attendance -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">This Week's Attendance</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Day</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @for ($i = 0; $i < 7; $i++)
                                @php
                                    $date = now()->subDays(6 - $i);
                                    $isToday = $date->isToday();
                                    $isWeekend = $date->isWeekend();
                                @endphp
                                <tr class="{{ $isToday ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4">{{ $date->format('M j') }}</td>
                                    <td class="px-6 py-4">{{ $date->format('l') }}</td>
                                    <td class="px-6 py-4">
                                        @if (!$isWeekend && $i < 5)
                                            <span
                                                class="{{ $isToday && isset($attendance) ? 'font-semibold text-blue-600' : 'text-gray-700' }}">
                                                {{ $isToday && isset($attendance) ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '9:00 AM' }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if (!$isWeekend && $i < 5)
                                            <span class="text-gray-700">
                                                {{ $i < 3 ? '5:00 PM' : ($i == 3 ? '4:30 PM' : '-') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if (!$isWeekend && $i < 5)
                                            <span class="text-gray-700">{{ $i == 4 ? '0' : '8.0' }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($isWeekend)
                                            <span
                                                class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Weekend</span>
                                        @elseif($i == 4)
                                            <span
                                                class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Absent</span>
                                        @elseif($i == 3)
                                            <span
                                                class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Present</span>
                                        @else
                                            <span
                                                class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">Present</span>
                                        @endif
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
