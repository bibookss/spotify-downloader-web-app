@extends ('layouts.app')

@section('content')

    <body>
        <div class="container sm:px-9 px-4 py-9 flex sm:flex-row flex-col gap-x-8">	
            <img class="md:w-[250px] md:h-[250px] w-[200px] h-[200px] sm:m-0 m-auto" src={{$playListData['image']}} alt="playlist-image">	
            <div class="container flex flex-col justify-end">	
                <p class="text-white sm:text-sm text-xs font-medium sm:pb-3 sm:pt-0 pt-6">Playlist</p>	
                <h1 class="text-white font-bold md:text-7xl sm:text-5xl text-xl sm:pb-10 pb-2">{{ $playListData['name'] }}</h1>	
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
            <form action="{{ route('download.playlist.server') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $playListData['id'] }}">
                <button class="bg-[#1ED760] w-12 h-12 rounded-full flex justify-center items-center">
                    <x-feathericon-download style="color: black" />
                </button>
            </form>
        </div>

        <div class="sm:px-9 px-4">
            <table class="table w-full">
                <thead class="sticky top-[60px]">
                    <tr class="border-b border-[#A2A2A2] sticky top-[70px] bg-[#2C0A0C]">
                        <th style="width: 4%" class="text-center font-normal py-4">#</th>
                        <th style="width: 36%" class="text-left font-normal py-4">Title</th>
                        <th style="width: 35%"
                            class="text-left font-normal md:table-cell hidden py-4">Album</th>
                        <th style="width: 10%"
                            class="text-left font-normal xl:table-cell hidden py-4">Date added</th>
                        <th style="width: 10%"
                            class="text-left font-normal lg:table-cell hidden py-4">Duration</th>
                        <th style="width: 5%">
                            <x-feathericon-download class="h-7 m-auto" />
                        </th>
                    </tr>
                </thead>
                <tbody class="pt-3">
                    @foreach ($playListData['tracks'] as $index => $track)
                        <tr class="list-hover">
                            <td class="text-center text-spotifyDescription">{{ $index + 1 }}</td>
                            <td>
                                <div class="container flex flex-row items-center py-2">
                                    <img class="w-12 h-12 mr-4 rounded" src={{ $track['image'] }} alt="track image">
                                    <div class="container flex flex-col">
                                        <p class="font-medium sm:text-base text-sm ">{{ $track['name'] }}</p>
                                        <p class="sm:text-sm text-xs font-regular text-spotifyDescription">
                                            {{ $track['artist'] }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="md:table-cell hidden text-spotifyDescription">{{ $track['album'] }}</td>
                            <td class="xl:table-cell hidden text-spotifyDescription">{{ $track['added_at'] }}</td>
                            <td class="lg:table-cell hidden text-spotifyDescription">{{ $track['duration'] }}</td>
                            <td class="text-center">
                                <form action="{{ route('download.song') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="title" value="{{ $track['name'] }}">
                                    <input type="hidden" name="artist" value="{{ $track['artist'] }}">
                                    <button type="submit" class="">
                                        <x-untitledui-download-circle class="w-5 h-5" style="color: #1ED760" />
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
        padding-top: 6rem;
        background: linear-gradient(180deg, #380609 0%, #230D0E 100%) fixed;
    }
</style>
