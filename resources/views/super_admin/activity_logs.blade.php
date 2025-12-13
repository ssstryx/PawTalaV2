@extends('layouts.super_admin.app')

@section('content')
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="fw-bold text-dark mb-1">Activity Logs</h4>
            <p class="text-secondary mb-0">Monitor system activities and events.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="min-height: 400px;">
        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
            <div class="text-secondary opacity-50 mb-3">
                <i class="bi bi-clipboard-data" style="font-size: 4rem;"></i>
            </div>
            <h5 class="text-secondary">No recent activity recorded.</h5>
        </div>
    </div>
@endsection