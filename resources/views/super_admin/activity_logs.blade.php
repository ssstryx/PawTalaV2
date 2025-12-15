@extends('layouts.super_admin.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800 fw-bold">Activity Logs</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h6 class="m-0 font-weight-bold text-primary">System Activities</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th>Date & Time</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Action</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td style="width: 180px;">
                                    {{ $activity->created_at->format('M d, Y h:i A') }}
                                    <div class="small text-muted">{{ $activity->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="fw-bold">{{ $activity->user_name }}</td>
                                <td>
                                    <span class="badge {{ $activity->role == 'super_admin' ? 'bg-primary' : ($activity->role == 'admin' ? 'bg-success' : 'bg-secondary') }}">
                                        {{ ucfirst($activity->role) }}
                                    </span>
                                </td>
                                <td class="fw-bold text-dark">{{ $activity->action }}</td>
                                <td class="text-muted small">{{ $activity->details ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-history fa-3x mb-3 opacity-25"></i>
                                    <p>No activity logs found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-3">
                {{ $activities->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection