#!/bin/bash
docker build -t web_split_logic .
docker run --name=web_split_logic --rm -p1337:80 -it web_split_logic
