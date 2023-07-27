from fastapi import FastAPI, HTTPException, Request
from fastapi.responses import FileResponse, StreamingResponse
import yt_dlp
import os
from youtubesearchpython import VideosSearch
from typing import List, Dict
import zipfile
import aiohttp
import asyncio
import aiofiles

app = FastAPI()

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

@app.post("/playlist/download")
async def download_playlist(request: Request, songs: List[Dict[str, str]]):
    if not songs:
        raise HTTPException(status_code=400, detail="No songs provided")

    async def generate_zip():
        # Create a temporary zip file to store the songs
        zip_file_path = 'songs.zip'
        with zipfile.ZipFile(zip_file_path, 'w') as zip_file:
            async with aiohttp.ClientSession() as session:
                tasks = []

                for song in songs:
                    title = song.get('title')
                    artist = song.get('artist')

                    if not title or not artist:
                        raise HTTPException(status_code=400, detail="Invalid song data")

                    link = get_youtube_url(title, artist)
                    tasks.append(download(session, link, title, artist))

                downloaded_songs = await asyncio.gather(*tasks)

                for song_path in downloaded_songs:
                    zip_file.write(song_path, os.path.basename(song_path))

                    # Clean up the downloaded song
                    os.remove(song_path)

        # Return the temporary zip file as a streaming response
        async with aiofiles.open(zip_file_path, mode='rb') as f:
            while True:
                chunk = await f.read(65536)  # Read in 64KB chunks (adjust the size as needed)
                if not chunk:
                    break
                yield chunk

        # Remove the temporary zip file after streaming is done
        os.remove(zip_file_path)

    response = StreamingResponse(generate_zip(), media_type='application/zip')
    response.headers["Content-Disposition"] = "attachment; filename=songs.zip"
    return response


####################

def get_youtube_url(title, artist):
    search = VideosSearch(f'{title}, by {artist}', limit=1)
    result = search.result()
    url = result['result'][0]['link']
    
    return url

async def download(session, link, title, artist):
    ydl_opts = {
        'format': 'm4a/bestaudio/best',
        'postprocessors': [{  
            'key': 'FFmpegExtractAudio',
            'preferredcodec': 'm4a',
        }],
        'outtmpl': f'downloads/{title}.%(ext)s'
    }

    with yt_dlp.YoutubeDL(ydl_opts) as ydl:
        await asyncio.to_thread(ydl.download, [link])

    path = f'downloads/{title}.m4a'
    if not os.path.exists(path):
        raise HTTPException(status_code=404, detail=f"{title} - {artist} not found")

    return path