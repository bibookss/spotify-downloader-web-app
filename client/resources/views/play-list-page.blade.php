@extends ('layouts.app')

@section('content')
<body>
    <div class="container sm:px-9 px-4 py-9 flex sm:flex-row flex-col gap-x-8">
        <img class="md:w-[250px] md:h-[250px] w-[200px] h-[200px] sm:m-0 m-auto" src={{$playListData['image']}} alt="playlist-image">
        <div class="container flex flex-col justify-end">
            <p class="text-white sm:text-sm text-xs font-medium sm:pb-3 sm:pt-0 pt-6">Playlist</p>
            <h1 class="text-white font-bold sm:text-7xl text-2xl sm:pb-10 pb-2">{{ $playListData['name'] }}</h1>
            <div class="container flex md:flex-row md:items-center md:justify-normal flex-col justify-center gap-x-2 gap-y-2 sm:pt-0 pt-5">
                <div class="flex flex-row items-center">
                    <img class="rounded-full w-8 h-8 mr-2" src="{{ session('spotifyUser')['image'] }}" alt="owner-image">
                    <p class="text-white font-semibold text-sm ">{{ $playListData['owner'] }}  <p class="md:block hidden">•</p></p>
                </div>
                <div class="flex flex-row gap-x-2">
                    <p class="text-white sm:text-sm text-xs font-normal">
                        {{ $playListData['num_tracks'] }} song{{ $playListData['num_tracks'] > 1 ? 's' : '' }},
                    </p>
                    <p class="text-white sm:text-sm text-xs font-normal">{{ $playListData['duration'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container sm:px-9 px-4 pb-8">
        <button class="bg-[#1ED760] w-12 h-12 rounded-full flex justify-center items-center">
            <x-feathericon-download style="color: black"/>
        </button>
    </div>

    <div class="sm:px-9 px-4">
        <table class="table w-full">
            <thead class="border-b border-[#A2A2A2]">
                <tr>
                    <th style="width: 3%" class="text-left pb-3">#</th>
                    <th style="width: 37%" class="text-left pb-3">Title</th>
                    <th style="width: 35%" class="text-left pb-3 md:table-cell hidden">Album</th>
                    <th style="width: 10%" class="text-left pb-3 xl:table-cell hidden">Date added</th>
                    <th style="width: 10%" class="text-left pb-3 lg:table-cell hidden">Duration</th>
                    <th style="width: 5%" class="text-center pb-3"><x-feathericon-download class="h-7"/></th>
                </tr>
            </thead>
            <tbody class="pt-3">
                @foreach($playListData['tracks'] as $index => $track)
                    <tr>
                        <td>{{ ($index + 1) }}</td>
                        <td>
                            <div class="container flex flex-row items-center py-2">
                                <img class="w-12 h-12 mr-4 rounded" src={{ $track['image'] }} alt="track image">
                                <div class="container flex flex-col">
                                    <p class="font-medium sm:text-base text-sm">{{ $track['name'] }}</p>
                                    <p class="sm:text-sm text-xs font-regular text-[#A2A2A2]">{{ $track['artist'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="md:table-cell hidden">{{ $track['album'] }}</td>
                        <td class="xl:table-cell hidden">{{ $track['added_at']}}</td>
                        <td class="lg:table-cell hidden">{{ $track['duration'] }}</td>
                        <td>
                            <form action="{{ route('download.song') }}" method="POST">
                                @csrf
                                <input type="hidden" name="title" value="{{ $track['name'] }}">
                                <input type="hidden" name="artist" value="{{ $track['artist'] }}"> 
                                <button type="submit">
                                    <x-untitledui-download-circle class="w-5 h-5" style="color: #1ED760"/>
                            </form>
                        </td>                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
@endsection

<style scoped>
    body {
        color: white;
        padding-top: 6rem;
        background: linear-gradient(180deg, #3A0609 0%, #121212 35.94%);
    }
</style>