<div>
    {{-- User's playlists --}}
    <div class="grid lg:grid-cols-3 grid-cols-2 sm:gap-4 gap-2 sm:mt-10 mt-6">
        @if (session()->has('spotifyPlaylists'))
            @foreach (session('spotifyPlaylists') as $playlist)
                <a href=" {{ route('spotify.playlist', ['id' => $playlist['id']]) }} "
                    class="flex bg-spotifyCard card-animation rounded-sm items-center drop-shadow-xl">
                    <!-- Playlist Image -->
                    <img src="{{ $playlist['image'] }}" alt="{{ $playlist['name'] }}" class="sm:w-20 sm:h-20 w-12 h-12 rounded-sm">

                    {{-- Playlist title --}}
                    <a href="{{ route('spotify.playlist', ['id' =>  $playlist['id']]) }}" class="text-white sm:text-base text-xs sm:font-bold font-medium ml-2">
                        {{ $playlist['name'] }}
                    </a>
                </div>
            @endforeach
        @endif
    </div>
</div>
