@extends ('layouts.app')

@section('content')

    <body class="antialiased">
        <div class="px-9 xl:pt-0 lg:pt-5 md:pt-8 sm:pt-10">
            <h1 class=" text-3xl text-white font-bold">Welcome back, {{ session('spotifyUser')['name'] }}! </h1>

            {{-- Render user's playlists --}}
            <x-playlist />

            <h1 class="text-2xl text-white font-bold my-10">Featured Playlists</h1>

            {{-- Render user's featured playlists --}}
            <x-featured-playlist />

            <h1 class="text-2xl text-white font-bold my-10">Category Playlists</h1>

            {{-- Render user's category playlist --}}

        </div>
    </body>
@endsection

<style scoped>
    body {
        background: linear-gradient(180deg, #3A0609 0%, #121212 35.94%);
    }
</style>
