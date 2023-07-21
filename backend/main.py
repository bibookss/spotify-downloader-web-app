from fastapi import FastAPI, HTTPException, Request
from fastapi.responses import FileResponse
import yt_dlp
import os
from youtubesearchpython import VideosSearch

def get_youtube_url(title, artist):
    search = VideosSearch(f'{title}, by {artist}', limit=1)
    result = search.result()
    url = result['result'][0]['link']
    
    return url


app = FastAPI()

@app.get("/api/download")
async def download(request: Request):
    title = request.query_params.get('title')
    artist = request.query_params.get('artist')

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


