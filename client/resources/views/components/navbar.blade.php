<div class="bg-transparent p-10 fixed z-50 w-screen flex justify-between">
    <img src="{{ asset('assets/logo/spot-logo.png') }}" class="h-8 w-auto" alt="">

    <div>
        @if (session()->has('spotifyAccessToken'))
            @if (session()->has('spotifyUser') && session('spotifyUser')['image'])
                <img src="{{ session('spotifyUser')['image'] }}" id="avatarButton" type="button"
                    data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start"
                    class="w-10 h-10 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500 cursor-pointer"
                    alt="User Image">

                
            @endif
            <form action="{{ route('spotify.logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-white">Logout</button>
            </form>
        @endif
    </div>
</div>
