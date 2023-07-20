from fastapi import FastAPI, HTTPException
from fastapi.responses import FileResponse
import yt_dlp
import os
from youtubesearchpython import VideosSearch

def get_youtube_url(title):
    search = VideosSearch(title, limit=1)
    result = search.result()
    url = result['result'][0]['link']
    
    return url


app = FastAPI()

@app.get("/")
async def root():
    return {"message": "Hello World"}

@app.get("/api/download/{title}")
async def download(title: str):
    link = get_youtube_url(title)

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
        raise HTTPException(status_code=404, detail="Video not found")

    return FileResponse(path, headers={"Content-Disposition": f"attachment; filename={title}.m4a"})

