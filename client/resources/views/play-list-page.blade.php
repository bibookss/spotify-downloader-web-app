@extends ('layouts.app')

@section('content')

    <body>
        {{-- Header --}}
        <div class="container sm:px-9 px-4 py-9 flex sm:flex-row flex-col gap-x-8">
            <img class="md:w-[250px] md:h-[250px] w-[200px] h-[200px] sm:m-0 m-auto" src={{ $playListData['image'] }}
                alt="playlist-image">
            <div class="container flex flex-col justify-end">
                <p class="text-white sm:text-sm text-xs font-medium sm:pb-3 sm:pt-0 pt-6">Playlist</p>
                <h1 class="text-white font-bold md:text-7xl sm:text-5xl text-xl sm:pb-10 pb-2">{{ $playListData['name'] }}
                </h1>
                <div
                    class="container flex md:flex-row md:items-center md:justify-normal flex-col justify-center gap-x-2 gap-y-2 sm:pt-0 pt-5">
                    <div class="flex flex-row items-center">
                        <img class="rounded-full w-8 h-8 mr-2" src="{{ $playListData['owner_picture'] }}" alt="owner-image">
                        <p class="text-white font-semibold text-sm ">
                            {{ $playListData['owner'] }}
                        <p class="md:block hidden">•</p>
                        </p>
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

        <div class="sm:px-9 px-4 pb-8">
            {{-- Bulk download button --}}
            <form id="downloadForm" action="{{ route('download.playlist.server') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $playListData['id'] }}">
                <button type="submit" class="bg-[#1ED760] w-12 h-12 rounded-full flex justify-center items-center">
                    <x-feathericon-download style="color: black" />
                </button>
            </form>

            {{-- Download progress bar --}}
            <div id="progressContainer" class="hidden">
                <div class="flex justify-between">
                    <span class="text-base font-medium text-spotifyGreen dark:text-white">Downloading your
                        playlist...</span>
                    <span id="progressPercent" class="text-sm font-medium text-spotifyGreen dark:text-white">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div id="progressBar" class="bg-spotifyGreen h-2.5 rounded-full" style="width: 0%" data-max="100%"></div>
                </div>
            </div>
        </div>

        <div class="sm:px-9 px-4">
            <table class="table w-full">
                <thead>
                    <tr class="border-b border-[#A2A2A2] sticky top-[85px] bg-[#121212]">
                        <th style="width: 4%" class="text-center font-normal py-4">#</th>
                        <th style="width: 36%" class="text-left font-normal py-4">Title</th>
                        <th style="width: 35%" class="text-left font-normal md:table-cell hidden py-4">Album</th>
                        <th style="width: 10%" class="text-left font-normal xl:table-cell hidden py-4">Date added</th>
                        <th style="width: 10%" class="text-left font-normal lg:table-cell hidden py-4">Duration</th>
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

        <x-footer />
    </body>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const downloadForm = document.getElementById("downloadForm");
        const progressContainer = document.getElementById("progressContainer");
        const progressBar = document.getElementById("progressBar");
        const progressPercent = document.getElementById("progressPercent");

        downloadForm.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent the default form submission

            // Show the progress bar
            progressContainer.classList.remove("hidden");

            // Start the playlist download initiation using AJAX
            fetch("{{ route('download.playlist.server') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}", // Add the CSRF token for Laravel protection
                },
                body: JSON.stringify({
                    id: "{{ $playListData['id'] }}", // Replace this with the playlist ID from your PHP variable
                }),
            })
            .then(response => response.json())
            .then(data => {
                // Handle the response as needed
                console.log("Initiate playlist download response:", data);
                // If you receive a download ID from the server, you can start checking the progress and download completion
                if (data.download_id) {
                    checkDownloadProgress(data.download_id);
                }
            })
            .catch(error => {
                console.error("Error initiating playlist download:", error);
            });
        });

        function checkDownloadProgress(downloadId) {
            setTimeout(() => {
                // Start fetching the progress value from the server after the initial delay
                const progressCheckInterval = setInterval(() => {
                    fetch("{{ route('download.playlist.progress') }}") // Fetch the progress from the server
                        .then(response => response.json())
                        .then(data => {
                            console.log("Progress data from server:", data); // Check the data received
                            const progress = data;
                            if (progress === "failed") {
                                // Download failed, stop checking progress
                                clearInterval(progressCheckInterval);
                                // Handle the failure, e.g., display an error message
                                console.error("Download failed.");
                                // Hide the progress bar after a brief delay (1 second in this example)
                                setTimeout(() => {
                                    progressContainer.classList.add("hidden");
                                }, 1000);
                            } else if (!isNaN(progress) && progress >= 0 && progress <= 100) {
                                // Update the progress bar for valid numeric progress values
                                progressBar.style.width = `${progress}%`;
                                progressPercent.textContent = `${progress}%`;

                                if (progress === 100) {
                                    // Download is complete, stop checking progress
                                    clearInterval(progressCheckInterval);

                                    window.location.href = "{{ route('download.playlist.client') }}";

                                    // Hide the progress bar after a brief delay (1 second in this example)
                                    setTimeout(() => {
                                        progressContainer.classList.add("hidden");
                                    }, 1000);
                                }
                            } else {
                                console.error("Invalid progress value:", progress);
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching progress:", error);
                        });
                }, 3000); // Check progress every 1 second (adjust as needed)
            }, 3000); // Initial delay of 10 seconds before the first progress check
        }
    });
</script>

<style scoped>
    body {
        color: white;
        padding-top: 6rem;
        background: #121212;
    }
</style>
