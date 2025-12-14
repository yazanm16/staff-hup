<!-- attendance/index.blade.php -->
@extends('layouts.app')

@section('title', 'Attendance')
@section('page-title', 'Attendance')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="space-y-6">
            @if (session('message'))
                <div
                    class="mb-4 px-4 py-3 rounded-lg
        @if (session('type') === 'success') bg-green-100 text-green-800
        @elseif (session('type') === 'warning')
            bg-yellow-100 text-yellow-800
        @else
            bg-red-100 text-red-800 @endif
    ">
                    {{ session('message') }}
                </div>
            @endif
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
                            <a href="{{ route('attendances.checkin.form') }}"
                                class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg flex items-center text-lg">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Check In
                            </a>
                        @else
                            <form method="POST" action="{{ route('attendances.checkout') }}">
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
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if (count($attendances) > 0)
                                @foreach ($attendances as $attend)
                                    <tr class="bg-blue-50">
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($attend->check_in)->format('M j') }}
                                        </td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($attend->check_in)->format('l') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-blue-600">
                                                {{ $attend->check_in->format('h:i A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-blue-600">
                                                {{ $attend->check_out ? \Carbon\Carbon::parse($attend->check_out)->format('h:i A') : '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $attend->work_hours }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
