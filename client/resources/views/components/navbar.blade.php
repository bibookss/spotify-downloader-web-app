<div class=" bg-transparent p-9 top-0 fixed w-screen z-20 flex justify-between items-center">
    <img src="{{ asset('assets/logo/spot-logo.png') }}" class="sm:block hidden h-8 w-auto" alt="Logo">
    <img src="{{ asset('assets/logo/logo.png') }}" class="sm:hidden block h-8 w-auto" alt="Logo">


    <div class="flex items-center">
        @if (session()->has('spotifyAccessToken'))
            <button type="button"
                class="text-black bg-spotify font-medium rounded-full text-sm px-8 py-2.5 text-center">
                <p class="sm:block hidden">Download Playlist</p> 
                <div class="sm:hidden flex items-center"><x-untitledui-download-circle class="inline h-4 mr-2"/>Playlist</div>
            </button>

            {{-- User's profile picture --}}
            @if (session()->has('spotifyUser') && session('spotifyUser')['image'])
                <div class="ml-5">
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
