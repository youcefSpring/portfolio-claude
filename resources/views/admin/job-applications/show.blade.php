@extends('layouts.admin-modern')

@section('title', $jobApplication->full_name)
@section('page-title', 'Application')

@section('content')
<div class="max-w-2xl">
    <a href="{{ route('admin.job-applications.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mb-4">
        <i class="fas fa-arrow-left mr-2"></i>All applications
    </a>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 lg:p-6 space-y-6">
        @include('admin.job-applications._details', ['application' => $jobApplication, 'showJob' => true, 'showDelete' => true])
    </div>
</div>
@endsection
