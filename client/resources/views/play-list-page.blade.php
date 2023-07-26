@extends ('layouts.app')

@section('content')
<body>
    <div class="container p-9 pt-[100px] flex flex-row gap-x-8">
        <img class="w-[250px] h-[250px]" src={{$playListData['image']}} alt="playlist-image">
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
        <button class="bg-[#1ED760] w-12 h-12 rounded-full flex justify-center items-center">
            <x-feathericon-download style="color: black"/>
        </button>
    </div>

    <div class="p-9">
        <table class="table w-full">
            <thead class="border-b border-[#A2A2A2]">
                <tr>
                    <th class="max-w-[10px] text-left pb-3">#</th>
                    <th class="max-w-auto text-left pb-3">Title</th>
                    <th class="max-w-auto text-left pb-3">Album</th>
                    <th class="max-w-auto text-left pb-3">Date added</th>
                    <th class="max-w-auto text-left pb-3">Duration</th>
                    <th class="min-w-[20px] text-center pb-3"><x-feathericon-download class="h-7"/></th>
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
                                    <p class="text-sm font-regular text-spotifyCardDescription">{{ $track['artist'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-spotifyCardDescription">{{ $track['album'] }}</td>
                        <td class="text-spotifyCardDescription">{{ $track['added_at']}}</td>
                        <td class="text-spotifyCardDescription">{{ $track['duration'] }}</td>
                        <td>
                            <form action="{{ route('download.song') }}" method="POST">
                                @csrf
                                <input type="hidden" name="title" value="{{ $track['name'] }}">
                                <input type="hidden" name="artist" value="{{ $track['artist'] }}">
                                <button type="submit">
                                    <x-untitledui-download-circle class="w-5 h-5" style="color: #1ED760"/>
                            </form>
                        </td>
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
