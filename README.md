## Acrhive Manager
A web-based archive manager written in Laravel framework. This is only for educational purpose - not suitable for production deployment without heavy configuration.

## Prerequisites
- Docker
- PHP 8.1.17 (required on local machine to generate app key)
- Port `8000` and `33061` must be available on the local machine

## Setup
1. Make a copy of `.env.example` as `.env`. See important notes:
    - Database host must be `DB_HOST=archive-db`
    - Do not use `DB_USERNAME=root`, change to a different username.
    - Enter any alphanumeric password in `DB_PASSWORD=`. Do not use special characters such as `$`.
    - For `APP_KEY`, do not manually create a random value. Use `php artisan key:generate` to generate a new value for `APP_KEY`.

2. Run `docker-compose up -d --build`, this may take a while

3. Run `docker exec -i archive-web composer install`

4. Run database migrations `docker exec -i archive-web php artisan migrate`

5. If you plan to continue development, install npm and its packages: `npm install && npm run dev`


## Troubleshooting

## APP_KEY
If you encounter an error screen about the `APP_KEY`, try `docker exec -i archive-web php artisan config:cache`.

## MAC OS with M1
MySQL image is known to problematic on M1 processors. So in `docker-compose.yml`, you can alternatively use MariaDB:
```
#image: mysql:5.7
image: mariadb:10.2
```

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
- vue-toast-notification - for toast notifications

## API Endpoints
All API Endpoints use the base url `localhost:8000/api/v1/`. Please include header `Accept:application/json`
| End Point | Method | Parameters | Description |
|-----------|--------|------------|-------------|
|`archive?page=<page_number>`|GET|`<page_number>` - the page number|Gets listing of archives that are uploaded.|
|`archive`|POST|`archive` - file from local machine to be uploaded|Uploaded selected `.zip` file. The response is the record of the uploaded file.|
|`archive/<id>`|DELETE|`<id>` - ID of the record|Delete both the record and the file from storage permanently.|
|`archive/<id>/download`|GET|`<id>` - ID of the record|Download the archive.|

## CURL Example
Below is what a CURL equivalent of a request response looks like:

### Request
```sh
curl --location 'localhost:8000/api/v1/archive?page=2'
```

### Response
```json
{
    "data": [
        {
            "id": 1,
            "created_at": "2023-04-10T00:41:12.000000Z",
            "updated_at": "2023-04-10T00:41:12.000000Z",
            "md5_checksum": "3dcc37d4693f655e697396ff315b371d",
            "original_name": "baz.zip",
            "user_id": null,
            "times_downloaded": 0,
            "path": "archives/iIvHdfnAqpd7DLsvZM3QOexdJCmRG5eWHk5yVHTo.zip",
            "size": 171
        },
        {
            "id": 2,
            "created_at": "2023-04-10T05:35:45.000000Z",
            "updated_at": "2023-04-10T05:35:45.000000Z",
            "md5_checksum": "a560034b05e6226d2bf6c428c75747c5",
            "original_name": "foobar.zip",
            "user_id": null,
            "times_downloaded": 0,
            "path": "archives/MgrYa4DxTINRavP90Y2tRRwpDJ8UQIfMnneicub5.zip",
            "size": 165
        }
    ],
    "links": {
        "first": "http://localhost:8000/api/v1/archive?page=1",
        "last": "http://localhost:8000/api/v1/archive?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://localhost:8000/api/v1/archive?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://localhost:8000/api/v1/archive",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}

```
