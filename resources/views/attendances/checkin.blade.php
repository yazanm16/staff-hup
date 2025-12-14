@extends('layouts.app')

@section('title', 'Check In')
@section('page-title', 'Check In')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">

        <h2 class="text-xl font-semibold mb-4 text-center">
            Confirm Check In
        </h2>

        <p class="text-gray-600 text-center mb-6">
            Click the button below to check in.
        </p>

        <form method="POST" action="{{ route('attendances.checkin') }}">
            @csrf

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg text-lg">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Check In Now
            </button>
        </form>

    </div>
@endsection
