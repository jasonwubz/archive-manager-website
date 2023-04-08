## Acrhive Manager
A web-based archive manager written in Laravel framework

## Prerequisites
- Docker
- PHP 8.1.17 (optional on local machine)

## Setup
1. Make a copy of `.env.example`

2. (optional) If you have PHP 8.1.17 on local machine, then run `composer install`

3. Run `docker-compose up -d --build`, this may take a while

4. If you skipped #2 then run `docker exec -i archive-web composer install`
