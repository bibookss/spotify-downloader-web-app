<div class="spotify-navbar bg-transparent p-9 top-0 fixed w-screen z-20 flex justify-between">
    <a href="{{ url('/dashboard') }}">
        <img src="{{ asset('assets/logo/spot-logo.png') }}" class="h-8 w-auto" alt="Logo">
    </a>

    <div class="flex">
        @if (session()->has('spotifyAccessToken'))
            <a type="button"
                class="text-black bg-spotify  font-medium rounded-full text-sm px-8 py-2.5 text-center mr-2 my-auto">
                Download Playlist</a>

            {{-- User's profile picture --}}
            @if (session()->has('spotifyUser') && session('spotifyUser')['image'])
                <div class="ml-5 mr-3 content-center items-center">
                    <img src="{{ session('spotifyUser')['image'] }}" id="avatarButton" type="button"
                        data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start"
                        class="w-10 h-10 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500 cursor-pointer"
                        alt="User Image">

                    <!-- Dropdown menu -->
                    <div id="userDropdown"
                        class="z-10 hidden bg-spotifyDark divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                        <div class="px-4 py-3 text-sm  text-white">
                            <div>{{ session('spotifyUser')['name'] }}</div>
                            <div class="font-medium truncate"> {{ session('spotifyUser')['email'] }} </div>
                        </div>
                        <ul class="py-2 text-sm text-white dark:text-gray-200" aria-labelledby="avatarButton">
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">Account</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">Settings</a>
                            </li>
                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-black">Profile</a>
                            </li>
                        </ul>

                        {{-- Logout button --}}
                        <div class="">
                            <form action="{{ route('spotify.logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="block px-4 py-2 text-sm text-white hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 hover:text-black"">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

<script>
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        var isAuthPage = $('body').hasClass('');

        if (scroll > 100) {
            $('.spotify-navbar').addClass('spotify-navbar-scrolled');
        } else {
            $('.spotify-navbar').removeClass('spotify-navbar-scrolled');
        }
    });
</script>

<style scoped>
    .spotify-navbar {
        background-color: transparent;
    }

    .spotify-navbar-scrolled {
        transition: background-color 0.5s ease-in-out;
        background-color: #181818;
        padding: 15px;
        padding-left: 37px;
        padding-right: 35px;
        align-content: center;
        align-items: center;
    }
</style>
