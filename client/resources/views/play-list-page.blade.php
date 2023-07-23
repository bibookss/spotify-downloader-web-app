@extends ('layouts.app')

@section('content')
<body>
    <div class="container p-9 pt-[100px] flex flex-row gap-x-8">
        <img class="w-[300px] h-[300px]" src={{$playListData['image']}} alt="playlist-image">
        <div class="container flex flex-col justify-end">
            <p class="text-white text-sm font-medium pb-3">Playlist</p>
            <h1 class="text-white font-bold text-7xl pb-10">{{ $playListData['name'] }}</h1>
            <div class="container flex flex-row items-center gap-x-2">
                <img class="rounded-full w-8 h-8 " src="{{ session('spotifyUser')['image'] }}" alt="owner-image">
                <p class="text-white font-semibold text-sm ">{{ $playListData['owner'] }}  •</p>
                <p class="text-white text-sm font-normal">
                    {{ $playListData['num_tracks'] }} song{{ $playListData['num_tracks'] > 1 ? 's' : '' }},
                </p>
                <p class="text-white text-sm font-normal">{{ $playListData['duration'] }}</p>
            </div>
        </div>
    </div>

    <div class="container px-9">
        <button class="bg-[#1ED760] w-12 h-12 rounded-full flex justify-center items-center color-black">
            <x-feathericon-download />
        </button>
    </div>

    <div class="container px-9">
        <table class="table-auto border-2 w-full">
            <thead>
              <tr>
                <th class="max-w-[10px]">#</th>
                <th>Title</th>
                <th>Album</th>
                <th>Date added</th>
                <th><x-wi-time-3 class="w-7 h-7"/></th>
              </tr>
            </thead>
            <tbody>
                @foreach($playListData['tracks'] as $index => $track)
                    <tr>
                        <td>{{ ($index + 1) }}</td>
                        <td>{{ $track['name'] }}</td>
                        <td>{{ $track['album'] }}</td>
                        <td>{{ $track['added_at']}}</td>
                        <td>{{ $track['duration'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
@endsection

<style scoped>
    body {
        color: white;
        background: linear-gradient(180deg, #3A0609 0%, #121212 35.94%);
    }
</style>