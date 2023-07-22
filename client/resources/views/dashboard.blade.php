@extends ('layouts.app')

@section('content')

    <body class=" antialiased">
        <div class="px-9 h-screen margin-top">
            <h1 class=" text-2xl text-white font-bold">Welcome back, {{ session('spotifyUser')['name'] }}! </h1>

            {{-- User's playlists --}}
            <div class="grid grid-cols-3 gap-4 mt-10">
                @if (session()->has('spotifyPlaylists'))
                    @foreach (session('spotifyPlaylists') as $playlist)
                        <div class="flex bg-spotifyCard rounded-sm">
                            <!-- Playlist Image -->
                            <img  src="{{ $playlist['image'] }}" alt="{{ $playlist['name'] }}" class="w-20 h-auto rounded-sm">

                            {{-- Playlist title --}}
                            <a href="https://open.spotify.com/playlist/{{ $playlist['id'] }}"
                                class="text-white font-bold">{{ $playlist['name'] }}</a>
                        </div>
                    @endforeach
                @endif
            </div>
    </body>
    </div>
@endsection

<style scoped>
    body {
        background: linear-gradient(180deg, #3A0609 0%, #121212 35.94%);
    }
</style>
