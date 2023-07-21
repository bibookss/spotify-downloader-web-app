<div class="bg-transparent p-10 fixed z-50 w-screen flex justify-between">
    <img src="{{ asset('assets/logo/spot-logo.png') }}" class="h-8 w-auto" alt="">

    <div>
        @if (session()->has('spotifyAccessToken'))
            <form action="{{ route('spotify.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-white">Logout</button>
            </form>
        @endif
    </div>
</div>
