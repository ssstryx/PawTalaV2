<section>
    <header>
        <h2 class="h4 font-weight-bold text-dark">
            {{ __('Update Profile Information') }}
        </h2>

        <p class="text-muted small">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        {{-- FIRST NAME --}}
        <div class="mb-3">
            <label for="first_name" class="form-label fw-bold">{{ __('First Name') }}</label>
            <input id="first_name" name="first_name" type="text" class="form-control" value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="first_name" />
            @error('first_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- MIDDLE NAME --}}
        <div class="mb-3">
            <label for="middle_name" class="form-label fw-bold">{{ __('Middle Name') }}</label>
            <input id="middle_name" name="middle_name" type="text" class="form-control" value="{{ old('middle_name', $user->middle_name) }}" autocomplete="middle_name" />
            @error('middle_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- LAST NAME --}}
        <div class="mb-3">
            <label for="last_name" class="form-label fw-bold">{{ __('Last Name') }}</label>
            <input id="last_name" name="last_name" type="text" class="form-control" value="{{ old('last_name', $user->last_name) }}" required autocomplete="last_name" />
            @error('last_name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- EMAIL --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-bold">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link p-0 align-baseline">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success small mt-2">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            {{-- SAVE BUTTON: Updated Color --}}
            <button type="submit" class="btn text-white" style="background-color: #E59500; border-color: #E59500;">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-muted small mb-0 ms-2">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>