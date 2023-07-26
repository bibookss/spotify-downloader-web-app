<div class="flex flex-nowrap gap-6 max-w-full overflow-x-auto my-scroll py-10">
    @if (session()->has('spotifyFeaturedPlaylists'))
        @foreach (session('spotifyFeaturedPlaylists') as $playlist)
            <div class="flex-shrink-0 w-48 h-65 p-5 items-center bg-spotifyCard rounded-md card-animation">
                    <!-- Display Playlist Image -->
                    <img src="{{ $playlist['image'] }}" alt="{{ $playlist['name'] }}" class="w-full h-40 object-cover">

                    <a href="{{ route('spotify.playlist', ['id' => $playlist['id']]) }}"
                        class="text-white font-bold mt-4 block overflow-hidden whitespace-nowrap text-overflow-ellipsis">{{ $playlist['name'] }}</a>
                    <p
                        class="text-spotifyCardDescription block overflow-hidden whitespace-nowrap text-overflow-ellipsis">
                        {{ $playlist['description'] }} </p>

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
