# Use the official Python image as the base image
FROM python:3.9

# Set the working directory in the container
WORKDIR /app

# Install FFmpeg
RUN apt-get update && apt-get install -y ffmpeg

# Copy FastAPI application files into the container
COPY . .

# Install Python dependencies (if any)
RUN pip install -r requirements.txt

# Expose port 8000 for FastAPI
EXPOSE 8001

# Start the FastAPI server (this will be managed by Docker Compose)
CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "8001"]
