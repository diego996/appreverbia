<nav class="d-flex gap-2 justify-content-end">
    @auth
        <a
            href="{{ url('/dashboard') }}"
            class="btn btn-primary btn-sm"
        >
            Dashboard
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="btn btn-outline-secondary btn-sm"
        >
            Log in
        </a>

        @if (Route::has('register'))
            <a
                href="{{ route('register') }}"
                class="btn btn-secondary btn-sm"
            >
                Register
            </a>
        @endif
    @endauth
</nav>
