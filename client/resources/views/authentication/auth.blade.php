@extends ('layouts.app')

@section('content')
<div class="relative flex justify-center items-center min-h-screen bg-spotifyDark">
    {{-- @if (Route::has('login'))
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                @auth
                    <a href="{{ url('/home') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                    @endif
                @endauth
            </div>
        @endif --}}

    <div class="top-0 right-0 absolute drop-shadow-2xl">
        <svg class="xl:w-[412px] xl:h-[452px] lg:w-[312px] lg:h-[352px] md:w-[212px] md:h-[252px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 410 548" fill="none">
            <path
                d="M340.792 -118.122C350.806 -114.548 358.873 -106.136 362.437 -95.6174L387.845 -20.6332L481.006 254.309C496.603 300.341 467.317 351.829 415.65 369.336C363.983 386.842 309.431 363.762 293.833 317.73C278.236 271.698 307.522 220.211 359.189 202.704C370.107 199.004 381.2 197.103 392.007 196.924L340.628 45.2895L118.166 209.834L191.637 426.664C207.234 472.696 177.948 524.183 126.281 541.69C74.6138 559.197 20.0618 536.117 4.4644 490.085C-11.133 444.053 18.1525 392.565 69.8198 375.058C80.7382 371.359 91.8313 369.458 102.638 369.279L47.377 206.188L21.9695 131.204C16.9938 116.519 21.6489 100.429 33.3627 91.7001L311.439 -113.981C319.872 -120.205 330.743 -121.799 340.792 -118.122Z"
                fill="#1ED760" />
        </svg>
    </div>

    <div class="bottom-0 left-0 absolute drop-shadow-2xl">
        <svg class="xl:w-[512px] xl:h-[352px] lg:w-[412px] lg:h-[352px] md:w-[312px] md:h-[252px]" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-2xl" viewBox="0 0 512 352" fill="none">
            <path
                d="M420 277C691.5 303.474 287.7 554 124.5 554C-38.7001 554 -171 429.983 -171 277C-171 124.017 -38.7001 0 124.5 0C287.7 0 179 253.5 420 277Z"
                fill="#1ED760" />
        </svg>
    </div>

    <div class="text-center text-white z-10">
        <img class="m-auto w-[60%] lg:w-[40%] md:w-[50%]" src="{{ asset('assets/logo/spot-logo.png') }}" alt="logo">

        <h1 class="text-3xl font-bold mt-5 px-5">
            Music Download <span class="text-spotify">Simplified</span>.
        </h1>

        <p class="font-light my-7 mx-auto px-10 md:w-[768px] text-center pb-5">Gone are the days of manual downloads of your favorite songs. Get ready for a
            seamless download
            experience with Spotify Downloader.</p>

        <a href="{{ route('spotify.login') }}" class="bg-spotifyGreen p-4 rounded-md text-black px-12">
            <i class="fa-brands fa-spotify"></i>
            Login with Spotify
        </a>
    </div>

</div>
@endsection
