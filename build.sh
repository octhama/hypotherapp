#!/bin/bash

rm -rf release
cp -r public/ release/
find release -type f -name '*.php' -delete

find public -type f -name '*.php' | while read file; do
  release_file=$(echo "$file" | sed -e 's/public/release/' -e 's/\.php$/.html/')
  currentdir=$(pwd)
  mkdir -p "$(dirname "$release_file")" && \
  (cd "$(dirname "$file")" && php -f "$(basename "$file")" > "$currentdir/$(dirname "$release_file")/$(basename "$release_file")")
done
