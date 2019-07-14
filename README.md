## Lapas
You can use this project as a laravel passport sample project or just test it as a full Restful project.

## Installation
Clone project:
```bash
git clone git@github.com:thisismahabadi/lapas.git
```

Run:
```bash
composer install
```

Create .env file and Copy .env.example to it:
```bash
cp .env.example .env
```

Migrate the database using:
```bash
php artisan migrate
```

Create the encryption keys needed to generate secure access tokens:
```bash
php artisan passport:install
```

and Finally serve the project:
```bash
php artisan serve
```

## Back-End Documentation
Available routes and http methods:

```bash
POST: /api/v1/register - register new user
POST: /api/v1/login - login user
POST: /api/v1/refresh - get new access token by refresh token
```

These routes need sending datas in body.

```bash
GET: /api/v1/posts - get all posts
POST: /api/v1/posts - create new post
GET: /api/v1/posts/{id} - get specific post using id
PUT: /api/v1/posts/{id} - edit specific post using id
DELETE: /api/v1/posts/{id} - delete specific post using id

POST: /api/v1/logout - logout current requested user
```

All of these routes are provided with auth:api middleware which means you should send Authorization field in request header.
 

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## Alternatives
Also I used [laravel-modules](https://github.com/nWidart/laravel-modules) for creating modular project, and [Backend with Laravel and Passport](https://www.youtube.com/watch?v=StFvAYmg04o&t=799s) video.

## License
[MIT](https://choosealicense.com/licenses/mit/)