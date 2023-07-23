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

    <div class="p-9">
        <table class="table w-full">
            <thead class="border-b border-[#A2A2A2]">
                <tr>
                    <th class="max-w-[10px] text-left pb-3">#</th>
                    <th class="text-left pb-3">Title</th>
                    <th class="text-left pb-3">Album</th>
                    <th class="text-left pb-3">Date added</th>
                    <th class="text-center pb-3"><x-wi-time-3 class="w-7 h-7"/></th>
                </tr>
            </thead>
            <tbody class="pt-3">
                @foreach($playListData['tracks'] as $index => $track)
                    <tr>
                        <td>{{ ($index + 1) }}</td>
                        <td>
                            <div class="container flex flex-row items-center py-1">
                                <img class="w-10 h-10 mr-4 rounded" src={{ $track['image'] }} alt="track image">
                                <div class="container flex flex-col">
                                    <p class="font-medium">{{ $track['name'] }}</p>
                                    <p class="text-sm font-regular text-[#A2A2A2]">{{ $track['artist'] }}</p>
                                </div>
                            </div>
                        </td>
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