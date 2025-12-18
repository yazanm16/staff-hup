@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')

    <div class="py-6 space-y-6 max-w-5xl mx-auto">

        <div class="p-6 bg-white shadow rounded-lg">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="p-6 bg-white shadow rounded-lg">
            @include('profile.partials.update-password-form')
        </div>

    </div>

@endsection
