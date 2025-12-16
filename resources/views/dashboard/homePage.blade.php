@extends('layouts.app')

@section('title', 'EmpManage')
@section('page-title', 'EmpManage')

@section('content')
    <h1 class="text-2xl font-bold text-gray-800 mb-3">
        Welcome to EmpManage Dashboard
    </h1>

    <p class="text-gray-600 leading-relaxed">
        EmpManage provides a centralized system for managing employees, monitoring attendance,
        and tracking task progress efficiently and securely.
    </p>

    @auth
        <p class="text-gray-500 mt-3">
            Use the navigation menu to access system features.
        </p>
    @else
        <p class="text-gray-500 mt-3">
            Please log in to access the system features and start managing your work.
        </p>
    @endauth



@endsection
