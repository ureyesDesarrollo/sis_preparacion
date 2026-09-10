#!/usr/bin/env bash

docker compose exec -T db mysql \
    -u codex \
    -p"$DB_CODEX_PASSWORD" \
    bd_sis_preparacion \
    -e "$1"