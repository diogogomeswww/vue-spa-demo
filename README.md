# Add Subscriber

App using Laravel 7.3.0 framework.


## Server Requirements

- PHP >= 7.2.5
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- SQlite3 PHP Extension


## Setup on local

Back-end:
- Clone repo
- run `composer install`
- create a local DB (mysql, sqlite, postgres,...) and edit the DB connection in .env
- run `php artisan migrate`

Front-end
- Create a virtual host with the document root equals to 'this project root folder' + 'public/ui-client/'
- Login credentials: 
    - email: johndoe@example.com
    - password: password


## TODO

- Frontend bug: edit field type is a free input text.
- Frontend: add/edit subscriber => set input type based on field's type: date, boolean... and validate the field
- Backend: add/edit subscriber => validate cast/validate type for each field's value  
- Confirmation dialog for delete actions
- Test responsive layout
- Add transitions
- UX - add click listener on table row => edit resource
- Fix routing issues when logging out, refreshing, going back in navigation,...
