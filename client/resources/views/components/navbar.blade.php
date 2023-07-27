<nav class="spotify-navbar top-0 fixed w-screen z-20 spotify-navbar" id="navbar">
    <div class=" flex flex-wrap items-center justify-between mx-auto py-5 sm:px-9 px-4">

        {{-- Logo --}}
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <img src="{{ asset('assets/logo/spot-logo.png') }}" class="sm:block hidden h-8 w-auto" alt="Logo">
            <img src="{{ asset('assets/logo/logo.png') }}" class="sm:hidden block h-8 w-auto" alt="Logo">
        </a>
        <div class="flex md:order-2">


            @if (session()->has('spotifyUser') && session('spotifyUser')['image'])
                {{-- Nav searchbar --}}
                <button type="button" data-collapse-toggle="navbar-search" aria-controls="navbar-search"
                    aria-expanded="false" class="md:hidden text-gray-300 rounded-lg text-sm p-2.5 mr-1">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only ">Search</span>
                </button>
                
                <form action="{{route('spotify.search')}}" method="GET" class="relative hidden md:block">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                        <span class="sr-only">Search icon</span>
                    </div>   

                    <input type="text" id="search-navbar" name="q"
                    class="block w-full p-2 pl-10 text-sm text-white border border-[#888888] rounded-lg bg-[#312828] focus:ring-gray-300 focus:border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search...">     
                    <input type="submit" style="display: none;">
                </form>

                {{-- User's profile picture --}}
                <div class="ml-5 md:mr-3 content-center items-center">
                    <img src="{{ session('spotifyUser')['image'] }}" id="avatarButton" type="button"
                        data-dropdown-toggle="userDropdown" data-dropdown-placement="bottom-start"
                        class="w-10 h-10 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500 cursor-pointer"
                        alt="User Image">

                    {{-- Dropdown menu --}}
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
        </div>

        {{-- @Noel, ano ni? --}}
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-search">
            <div class="relative mt-3 md:hidden">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="text" id="search-navbar"
                    class="block w-full p-2 pl-10 text-sm text-white border border-[#888888] bg-[#312828] rounded-lg focus:ring-gray-300 focus:border-gray-300"
                    placeholder="Search...">
            </div>
        </div>
    </div>
</nav>

<script>
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        var isAuthPage = $('body').hasClass('');

        if (scroll > 0) {
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
        backdrop-filter: blur(8px);
        /* Add blur effect */
        background-color: rgba(0, 0, 0, 0.6);/
    }
</style>
