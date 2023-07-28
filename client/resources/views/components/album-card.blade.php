<div class="flex-shrink-0 sm:w-48 w-40 h-65 p-5 items-center bg-spotifyCard rounded-md card-animation">
    <!-- Display album Image -->
    @if($album['image'] !== null)
        <img src="{{ $album['image'] }}" alt="{{ $album['name'] }}" class="sm:w-40 sm:h-40 w-32 h-32 object-cover rounded-md">
    @else
        <img src="{{ asset('assets/img/no_album_image.png') }}" alt="Placeholder" class="sm:w-40 sm:h-40 w-32 h-32 object-cover rounded-md">
    @endif

    <a href="{{ route('spotify.album', ['id' => $album['id']]) }}"
        class="text-white sm:text-base text-sm font-bold mt-4 block overflow-hidden whitespace-nowrap text-overflow-ellipsis">{{ $album['name'] }}
    </a>

    @if (isset($album['release_date']))
        <p class="text-spotifyDescription block overflow-hidden whitespace-nowrap text-overflow-ellipsis sm:text-base text-xs">
            {{ $album['release_date'] }}
        </p>
    @endif
</div>