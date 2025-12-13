@extends(Auth::user()->role === 'super_admin' ? 'layouts.super_admin.app' : 'layouts.admin.app')

@section('content')
<style>
    body {
        background-color: #FAEBCF; /* Set the background color for this specific page */
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-height: 150px;">
            </div>

            <!-- Profile Information Card -->
            <div class="card shadow-sm rounded-lg mb-4">
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="card shadow-sm rounded-lg mb-4">
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="card shadow-sm rounded-lg">
                <div class="card-body p-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
