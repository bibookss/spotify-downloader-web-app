<div class="flex-shrink-0 sm:w-48 w-40 h-65 p-5 items-center bg-spotifyCard rounded-md card-animation">
    <!-- Display Playlist Image -->
    @if ($playlist['image'] !== null)
        <img src="{{ $playlist['image'] }}" alt="{{ $playlist['name'] }}"
            class="sm:w-40 sm:h-40 w-32 h-32 object-cover rounded-md">
    @else
        <img src="{{ asset('assets/img/no_playlist_image.png') }}" alt="Placeholder"
            class="sm:w-40 sm:h-40 w-32 h-32 object-cover rounded-md">
    @endif

    <a href="{{ route('spotify.playlist', ['id' => $playlist['id']]) }}"
        class="text-white sm:text-base text-sm font-bold mt-4 block overflow-hidden whitespace-nowrap text-overflow-ellipsis">{{ $playlist['name'] }}
    </a>

    @if (isset($playlist['description']))
        <p
            class="text-spotifyDescription block overflow-hidden whitespace-nowrap text-overflow-ellipsis sm:text-base text-xs">
            {{ $playlist['description'] }}
        </p>
    @endif

    <!-- Display artists -->
    @if (isset($playlist['artists']))
        @foreach ($playlist['artists'] as $artist)
            <p>{{ $artist }}</p>
        @endforeach
    @endif
</div>
