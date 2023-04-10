## Acrhive Manager
A web-based archive manager written in Laravel framework. This is only for educational purpose - not suitable for production deployment without heavy configuration.

## Prerequisites
- Docker
- PHP 8.1.17 (optional on local machine)
- Port `8000` and `33061` must be available on the local machine

## Setup
1. Make a copy of `.env.example` as `.env`. See important notes:
    - Database host must be `DB_HOST=archive-db`
    - Do not use `DB_USERNAME=root`, change to a different username.
    - Enter any alphanumeric password in `DB_PASSWORD=`. Do not use special characters such as `$`.

2. (optional) If you have PHP 8.1.17 on local machine, then run `composer install`

3. Run `docker-compose up -d --build`, this may take a while

4. If you skipped #2 then run `docker exec -i archive-web composer install`

5. Run database migrations `docker exec -i archive-web php artisan migrate`

6. If you plan to continue development, install npm and its packages: `npm install && npm run dev`

## Technical Overview
In this application, I used the following LAMP stack:
- Debian Bullseye
- Apache 2.4.56
- MySQL 5.7
- PHP 8.1.17

The following compoments are added:
- laravel/ui via composer - for installing Bootstrap CSS and Vue
- moment - for datetime formatting
- font awesome - commonly used icons
- bootstrap-vue - for adding modal boxes (avoiding jQuery implementation)
- [drag and drop feature](https://blog.logrocket.com/customizing-drag-drop-file-uploading-vue/) - not a third party library, but a referenced used to help build the drag and drop

## API Endpoints
All API Endpoints use the base url `localhost:8000/api/v1/`.
| End Point | Method | Parameters | Description |
|-----------|--------|------------|-------------|
|`archive?page=<page_number>`|GET|`<page_number>` - the page number|Gets listing of archives that are uploaded|
|`archive`|POST|`archive` - file from local machine to be uploaded|Uploaded selected `.zip` file|
|           |        |            |             |
