from fastapi import FastAPI, HTTPException, Request
from fastapi.responses import FileResponse, StreamingResponse
import yt_dlp
import os
from youtubesearchpython import VideosSearch
from typing import List, Dict
import zipfile
import aiohttp
import asyncio
import uuid
import threading

app = FastAPI()

download_status = {}
download_status_lock = threading.Lock()

# Single song download
@app.post("/song/download")
async def download_song(request: Request, song: Dict[str, str]):
    if not song:
        raise HTTPException(status_code=400, detail="No songs provided")
    
    title = song.get('title')
    artist = song.get('artist')

    link = get_youtube_url(title, artist)

    ydl_opts = {
        'format': 'm4a/bestaudio/best',
        'postprocessors': [{  
            'key': 'FFmpegExtractAudio',
            'preferredcodec': 'm4a',
        }],
        'outtmpl': f'downloads/{title}.%(ext)s'
    }

    with yt_dlp.YoutubeDL(ydl_opts) as ydl:
        ydl.download([link])

    path = f'downloads/{title}.m4a'
    if not os.path.exists(path):
        raise HTTPException(status_code=404, detail="Song not found")

    return FileResponse(path, headers={"Content-Disposition": f"attachment; filename={title}.m4a"})

# Playlist download (server-side)
@app.post("/playlist/download/server")
async def download_playlist_server(request: Request, songs: List[Dict[str, str]]):
    if not songs:
        raise HTTPException(status_code=400, detail="No songs provided")

    # Generate a unique ID for this download
    download_id = str(uuid.uuid4())

    # Start the download in the background
    asyncio.create_task(perform_playlist_download(songs, download_id))

    return {"message": "Server-side download started", "download_id": download_id}

# Check download status
@app.get("/playlist/download/status/{download_id}")
async def check_download_status(download_id: str):
    with download_status_lock:
        status = download_status.get(download_id)

    if status is None:
        raise HTTPException(status_code=404, detail="Download ID not found")

    return {"download_id": download_id, "status": status, "songs": download_status.get(download_id + "_songs"), "zip_file_path": download_status.get(download_id + "_zip_file_path")}

# Playlist download (client-side)
@app.get("/playlist/download/client")
async def download_playlist_client(download_id: str):
    # Here, you can check the status of the download using the `download_id`
    status_response = await check_download_status(download_id)

    if status_response.get("status") != "100":
        # Return an appropriate response indicating the download is not yet completed
        return {"message": "Download in progress. Please try again later."}

    # Get the songs associated with the download_id
    songs = status_response.get("songs")
    zip_file_path = status_response.get("zip_file_path")

    # Return the zip file as a FileResponse and status code 200 if the download is completed
    if not zip_file_path:
        raise HTTPException(status_code=404, detail="Zip file not found")

    return FileResponse(zip_file_path, media_type='application/zip', filename=f"{download_id}.zip")


# Helper functions
async def perform_playlist_download(songs: List[Dict[str, str]], download_id: str):
    try:
        total_songs = len(songs)

        # Update the download status as "in_progress" when the download starts
        with download_status_lock:
            download_status[download_id] = "0"
            download_status[download_id + "_songs"] = songs  # Store the songs data

        # Create a list to store download tasks
        tasks = []

        async with aiohttp.ClientSession() as session:
            for song in songs:
                title = song.get('title')
                artist = song.get('artist')

                if not title or not artist:
                    raise HTTPException(status_code=400, detail="Invalid song data")

                link = get_youtube_url(title, artist)
                tasks.append(download(session, link, title, artist, download_id, total_songs))

            # Wait for all download tasks to complete
            await asyncio.gather(*tasks)

        # Update the download status as "completed" when the download is finished
        with download_status_lock:
            download_status[download_id] = "100"

             # Zip the songs from the "downloads" folder
            zip_file_path = f"downloads/{download_id}_songs.zip"
            with zipfile.ZipFile(zip_file_path, 'w') as zip_file:
                for song in songs:
                    title = song.get('title')
                    song_file_path = f"downloads/{title}.m4a"
                    if os.path.exists(song_file_path):
                        zip_file.write(song_file_path, os.path.basename(song_file_path))
            
            download_status[download_id + "_zip_file_path"] = zip_file_path

            # Delete the downloaded songs
            for song in songs:
                title = song.get('title')
                song_file_path = f"downloads/{title}.m4a"
                if os.path.exists(song_file_path):
                    os.remove(song_file_path)
            

    except Exception as e:
        print(f"Error in server-side download: {e}")
        # Optionally, you can log the error or take other actions

        # Update the download status as "failed" if an error occurs
        with download_status_lock:
            download_status[download_id] = "failed"

async def download(session, link, title, artist, download_id,  total_songs):
    ydl_opts = {
        'format': 'm4a/bestaudio/best',
        'postprocessors': [{  
            'key': 'FFmpegExtractAudio',
            'preferredcodec': 'm4a',
        }],
        'outtmpl': f'downloads/{title}.%(ext)s'  # Modified to include download_id in filename
    }

    with yt_dlp.YoutubeDL(ydl_opts) as ydl:
        await asyncio.to_thread(ydl.download, [link])

    path = f'downloads/{title}.m4a'  # Modified to include download_id in filename
    if not os.path.exists(path):
        raise HTTPException(status_code=404, detail=f"{title} - {artist} not found")

    # Update the download progress for the song
    with download_status_lock:
        completed_songs = download_status.get(download_id + "_completed_songs", 0) + 1
        download_status[download_id + "_completed_songs"] = completed_songs
    
        # Calculate the download progress as a percentage
        progress = int((completed_songs / total_songs) * 100)

        # Update the download status with the progress percentage
        download_status[download_id] = f"{progress}"

    return path

def get_youtube_url(title, artist):
    search = VideosSearch(f'{title}, by {artist}', limit=1)
    result = search.result()
    url = result['result'][0]['link']
    
    return url
