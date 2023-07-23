<div>
    {{-- User's playlists --}}
    <div class="grid grid-cols-3 gap-4 mt-10">
        @if (session()->has('spotifyPlaylists'))
            @foreach (session('spotifyPlaylists') as $playlist)
                <div class="flex bg-spotifyCard rounded-sm items-center drop-shadow-xl">
                    <!-- Playlist Image -->
                    <img src="{{ $playlist['image'] }}" alt="{{ $playlist['name'] }}" class="w-20 h-auto rounded-sm">

                    {{-- Playlist title --}}
                    <a href="{{ route('spotify.playlist', ['id' =>  $playlist['id']]) }}"
                        class="text-white font-bold indent-10">{{ $playlist['name'] }}</a>
                </div>
            @endforeach
        @endif
    </div>
</div>
