@extends ('layouts.app')

@section('content')

    <body class="antialiased">
        <div class="sm:px-9 px-4 xl:pt-0 lg:pt-5 md:pt-8 sm:pt-10">
            <h1 class="sm:text-3xl text-xl text-white font-bold">Playlists</h1>

            @if(isset($playlists))
                <div class="flex flex-nowrap sm:gap-6 gap-4 max-w-full overflow-x-auto my-scroll sm:py-10 py-6">
                    @foreach ($playlists as $playlist)
                        <x-play-list-card :playlist="$playlist"/>
                    @endforeach
                </div>
            @endif

        </div>
    </body>
@endsection

<style scoped>
    body {
        padding-top: 8rem;
        background: linear-gradient(180deg, #3A0609 0%, #121212 35.94%);
    }
</style>
