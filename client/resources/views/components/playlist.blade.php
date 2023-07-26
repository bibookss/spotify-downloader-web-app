<div>
    {{-- User's playlists --}}
    <div class="grid lg:grid-cols-3 grid-cols-2 sm:gap-4 gap-2 sm:mt-10 mt-6">
        @if (session()->has('spotifyPlaylists'))
            @foreach (session('spotifyPlaylists') as $playlist)
                <a href=" {{ route('spotify.playlist', ['id' => $playlist['id']]) }} "
                    class="flex bg-spotifyCard card-animation rounded-sm items-center drop-shadow-xl">
                    <!-- Playlist Image -->
                    <img src="{{ $playlist['image'] }}" alt="{{ $playlist['name'] }}" class="w-20 h-auto rounded-sm">

                    {{-- Playlist title --}}
                    <p class="text-white font-bold indent-10"> {{ $playlist['name'] }} </p>
                </a>
            @endforeach
        @endif
    </div>
</div>
