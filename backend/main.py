from fastapi import FastAPI, HTTPException, Request
from fastapi.responses import FileResponse, StreamingResponse
import yt_dlp
import os
from youtubesearchpython import VideosSearch
from typing import List, Dict
import zipfile
from io import BytesIO


def get_youtube_url(title, artist):
    search = VideosSearch(f'{title}, by {artist}', limit=1)
    result = search.result()
    url = result['result'][0]['link']
    
    return url


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

    # Create an in-memory zip file
    zip_file_buffer = BytesIO()

    with zipfile.ZipFile(zip_file_buffer, 'w') as zip_file:
        for song in songs:
            title = song.get('title')
            artist = song.get('artist')

            if not title or not artist:
                raise HTTPException(status_code=400, detail="Invalid song data")

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
                raise HTTPException(status_code=404, detail=f"{title} - {artist} not found")

            # Add the downloaded song to the zip archive
            zip_file.write(path, os.path.basename(path))

            # Clean up the downloaded song
            os.remove(path)

    # Seek back to the beginning of the zip file buffer
    zip_file_buffer.seek(0)

    # Return the zip file as a StreamingResponse
    return StreamingResponse(zip_file_buffer, media_type='application/zip', headers={"Content-Disposition": "attachment; filename=songs.zip"})