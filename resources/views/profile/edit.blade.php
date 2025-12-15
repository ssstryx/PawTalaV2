@extends(Auth::user()->role === 'super_admin' ? 'layouts.super_admin.app' : (Auth::user()->role === 'admin' ? 'layouts.admin.app' : 'layouts.user.app'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Profile Information Card -->
            <div class="card shadow-sm rounded-lg mb-4">
                <div class="card-body p-4">
                    @php
                        $profile = Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin' ? Auth::user()->adminProfile : Auth::user()->userProfile;
                    @endphp
                    @include('profile.partials.update-profile-information-form', ['profile' => $profile])
                </div>
            </div>

            <!-- Update Password Card -->
            <div class="card shadow-sm rounded-lg mb-4">
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
