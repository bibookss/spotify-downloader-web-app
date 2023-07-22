# spotify-downloader-web-app
A Laravel web app to download songs from your favorite Spotify artists, albums, and playlists using a Python backend service.

## Features
- [ ] Download songs from playlists, albums, or artists. 
- [ ] Download whole playlists, albums, or artist's songs in one click.
- [ ] Search for songs, albums, and playlists
- [ ] View popular playlists from Spotify

## Installation
#### Backend
```
cd backend
pip install -r requirements.txt
brew install ffmpeg
```

### *Frontend Team:*
- [**Martin Edgar Atole**](https://github.com/CS-Martin) (Lead)

### *Backend Team:*
- [**Albert Perez**](https://github.com/bibookss) (Lead)
- 
---

## *Conventions*
1. *Github*
    - Commits:
        
        git commit -m [action]: [description]
        
        - Action:
            | Option | Information |
            | :---: | :--- |
            | feat        | New feature for the user, not a new feature for build script.         |
            | fix         | Bug fix for the user, not a fix to a build script.                    |
            | docs        | Changes to the documentation.                                         |
            | style       | Formatting, missing semi colons, etc; no production code change       |
            | refactor    | Refactoring production code, eg. renaming a variable                  |
            | test        | Adding missing tests, refactoring tests; no production code change    |
            | chore       | Updating grunt tasks etc; no production code change.                  |
            
    - Branching:
        
            git branch '[layer]/[description]' '[commit-hash]'
            --- or ---
            git checkout -b '[layer]/[description]' '[commit-hash]'
        
        - Layer:
            - frontend - A branch that concerns the frontend (*presentation layer*) of the project.
            - backend - A branch that concerns the backend (*data access layer*) of the project.
        - Description:
            - Options: feature, description, or bugfix.
        - Commit Hash (Optional):
            - Create a branch of [layer]/[description] from a previous commit using the [commit-hash].
---

## Usage
#### Backend
Run the backend service
```
cd backend
uvicorn main:app
```

#### Frontend
