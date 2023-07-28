@extends ('layouts.app')

@section('content')

    <body>
        {{-- Header --}}
        <div class="container sm:px-9 px-4 py-9 flex sm:flex-row flex-col gap-x-8">
            <img class="md:w-[250px] md:h-[250px] w-[200px] h-[200px] sm:m-0 m-auto" src={{ $albumData['image'] }}
                alt="playlist-image">
            <div class="container flex flex-col justify-end">
                <p class="text-white sm:text-sm text-xs font-medium sm:pb-3 sm:pt-0 pt-6">Album</p>
                <h1 class="text-white font-bold md:text-7xl sm:text-5xl text-xl sm:pb-10 pb-2">{{ $albumData['name'] }}
                </h1>
                <div
                    class="container flex md:flex-row md:items-center md:justify-normal flex-col justify-center gap-x-2 gap-y-2 sm:pt-0 pt-5">
                    <div class="flex flex-row items-center">
                        <img class="rounded-full w-8 h-8 mr-2" src="{{ session('spotifyUser')['image'] }}" alt="owner-image">
                        <p class="text-white font-semibold text-sm ">{{ $albumData['artist'] }}
                        <p class="md:block hidden">•</p>
                        </p>
                    </div>
                    <div class="flex flex-row gap-x-2">
                        <p class="text-white sm:text-sm text-xs font-normal">
                            {{ $albumData['num_tracks'] }} song{{ $albumData['num_tracks'] > 1 ? 's' : '' }},
                        </p>
                        <p class="text-white sm:text-sm text-xs font-normal">{{ $albumData['duration'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="sm:px-9 px-4 pb-8">
            {{-- Bulk download button --}}
            <form id="downloadForm" action="{{ route('download.playlist.server') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $albumData['id'] }}">
                <button type="submit" class="bg-[#1ED760] w-12 h-12 rounded-full flex justify-center items-center">
                    <x-feathericon-download style="color: black" />
                </button>
            </form>

            {{-- Download progress bar --}}
            <div id="progressContainer" class="">
                <div class="flex justify-between">
                    <span class="text-base font-medium text-spotifyGreen dark:text-white">Downloading your
                        playlist...</span>
                    <span id="progressPercent" class="text-sm font-medium text-spotifyGreen dark:text-white">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div id="progressBar" class="bg-spotifyGreen h-2.5 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <div class="sm:px-9 px-4">
            <table class="table w-full">
                <thead>
                    <tr class="border-b border-[#A2A2A2] sticky top-[70px] bg-[#121212]">
                        <th style="width: 4%" class="text-center font-normal py-4">#</th>
                        <th style="width: 70%" class="text-left font-normal py-4">Title</th>
                        <th style="width: 20%" class="text-left font-normal lg:table-cell hidden py-4">Duration</th>
                        <th style="width: 6%">
                            <x-feathericon-download class="h-7 m-auto" />
                        </th>
                    </tr>
                </thead>
                <tbody class="pt-3">
                    @foreach ($albumData['tracks'] as $index => $track)
                        <tr class="list-hover">
                            <td class="text-center text-spotifyDescription py-3">{{ $index + 1 }}</td>
                            <td class="font-medium">
                                {{-- <div class="container flex flex-row items-center py-2">
                                    <img class="w-12 h-12 mr-4 rounded" src={{ $track['image'] }} alt="track image">
                                    <div class="container flex flex-col">
                                        <p class="font-medium sm:text-base text-sm ">{{ $track['name'] }}</p>
                                        <p class="sm:text-sm text-xs font-regular text-spotifyDescription">
                                            {{ $track['artist'] }}
                                        </p>
                                    </div>
                                </div> --}}
                                {{ $track['name'] }}
                            </td>
                            <td class="lg:table-cell hidden text-spotifyDescription">{{ $track['duration'] }}</td>
                            <td class="text-center">
                                <form action="{{ route('download.song') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="title" value="{{ $track['name'] }}">
                                    <input type="hidden" name="artist" value="{{ $albumData['artist'] }}">

                                    {{-- Single download button --}}
                                    <button type="submit">
                                        <x-untitledui-download-circle class="w-5 h-5 text-spotifyGreen"  />
                                    </button>


                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="sm:px-9 px-4" style="padding-top: 50px;">
            <h1 class="sm:text-3xl text-xl text-white font-bold">More by {{$albumData['artist']}}</h1>
            @if(isset($albumData['artist_albums']))
                <div class="flex flex-nowrap sm:gap-6 gap-4 max-w-full overflow-x-auto my-scroll sm:py-10 py-6">
                    @foreach ($albumData['artist_albums'] as $album)
                        <x-album-card :album="$album"/>
                    @endforeach
                </div>
            @endif
        </div>

    </body>
@endsection

<script>
    $(document).ready(function() {
        $("#downloadForm").submit(function(event) {
            event.preventDefault();

            // Show progress bar
            $("#progressContainer").removeClass("hidden");

            // Send AJAX request
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: $(this).serialize(),
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();

                    // Download progress
                    xhr.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            // lagay mo nalang dito
                            var percentComplete = evt.loaded / evt.total * 100;
                            // Update progress bar
                            $("#progressBar").width(percentComplete + '%');
                            $("#progressPercent").text(Math.round(percentComplete) +
                                '%');
                        }
                    }, false);

                    return xhr;
                },
                success: function(data) {
                    // Handle the response from the server
                },
                error: function(data) {
                    // Handle errors
                }
            });
        });
    });
</script>

<style scoped>
    body {
        color: white;
        padding-top: 6rem;
        background: #121212;
    }
</style>
