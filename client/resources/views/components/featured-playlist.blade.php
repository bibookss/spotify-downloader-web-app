<div class="flex flex-nowrap sm:gap-6 gap-4 max-w-full overflow-x-auto my-scroll sm:py-10 py-6">
    @if (session()->has('spotifyFeaturedPlaylists'))
        @foreach (session('spotifyFeaturedPlaylists') as $playlist)
            <div class="flex-shrink-0 sm:w-48 w-40 h-65 p-5 items-center bg-spotifyCard rounded-md card-animation">
                <!-- Display Playlist Image -->
                <img src="{{ $playlist['image'] }}" alt="{{ $playlist['name'] }}" class="sm:w-40 sm:h-40 w-32 h-32 object-cover rounded-md">

                <a href="{{ route('spotify.playlist', ['id' =>  $playlist['id']]) }}"
                    class="text-white sm:text-base text-sm font-bold mt-4 block overflow-hidden whitespace-nowrap text-overflow-ellipsis">
                    {{ $playlist['name'] }}
                </a>
                
                <p class="text-spotifyCardDescription block overflow-hidden whitespace-nowrap text-overflow-ellipsis sm:text-base text-xs">
                     {{ $playlist['description'] }} 
                </p>
                    <!-- Display artists -->
                    @if (isset($playlist['artists']))
                        @foreach ($playlist['artists'] as $artist)
                            <p>{{ $artist }}</p>
                        @endforeach
                    @endif
                </div>
            </a>
        @endforeach
    @endif
</div>
