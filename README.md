# Contact List (Laravel)

The main purpose of the application is to organize contacts.

## Set up

1) Clone the repo
2) Run `composer install`
3) Run `php artisan key:generate`
4) Run `php artisan jwt:secret` - to generate jwt token
4) Run `cp .env.example .env`
4) Setup `MAIL_RECEIVER_ADDRESS` in `.env` file 
4) Setup databases config
4) Run `php artisan migrate`

### Seed the users and contacts

`php artisan db:seed`  
will create contacts and users with main roles

- admin (login - `admin@examle.com`, password - `password`)
- admin (login - `moderator@examle.com`, password - `password`)
- admin (login - `viewer@examle.com`, password - `password`)

## Testing

1) To run tests write in console: `php artisan test`
