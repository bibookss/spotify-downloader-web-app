#!/bin/bash

# Get the directory of the script
script_dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

# Define the relative directory to search for files (e.g., "downloads" in the same directory as the script)
relative_search_dir="/"

# Construct the absolute search directory path
search_dir="$script_dir/$relative_search_dir"

# Find and delete one-day-old zip files
find "$search_dir" -type f -name "*.zip" -mtime +0 -exec rm -f {} \;

# Find and delete one-day-old m4a files
find "$search_dir" -type f -name "*.m4a" -mtime +0 -exec rm -f {} \;
