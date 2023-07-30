@extends ('layouts.app')

@section('content')

    <body class="antialiased">
        <div class="sm:px-9 px-4 xl:pt-0 lg:pt-5 md:pt-8 sm:pt-10">
            <h1 class="sm:text-3xl text-xl text-white font-bold">Welcome back, {{ session('spotifyUser')['name'] }}! </h1>

            {{-- Render user's playlists --}}
            <x-playlist />

            <h1 class="sm:text-3xl text-xl text-white font-bold mt-10">Featured Playlists</h1>

            {{-- Render user's featured playlists --}}
            <div class="flex flex-nowrap sm:gap-6 gap-4 max-w-full overflow-x-auto my-scroll sm:py-10 py-6">
                @if (session()->has('spotifyFeaturedPlaylists'))
                    @foreach (session('spotifyFeaturedPlaylists') as $playlist)
                        <x-play-list-card :playlist="$playlist"/>
                    @endforeach
                @endif
            </div>

            <h1 class="sm:text-3xl text-xl text-white font-bold mt-10">Category Playlists</h1>
            {{-- Render user's category playlist --}}
            <div class="flex flex-nowrap sm:gap-6 gap-4 max-w-full overflow-x-auto my-scroll sm:py-10 py-6">
                @if (session()->has('spotifyCategories'))
                    @foreach (session('spotifyCategories') as $category_playlist)
                        <x-play-list-card :playlist="$category_playlist"/>
                    @endforeach
                @endif
            </div>
        </div>
    </body>
@endsection

<style scoped>
    body {
        padding-top: 8rem;
        background: linear-gradient(180deg, #3A0609 0%, #121212 35.94%);
    }
</style>
