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

### Routes and Methods

Available routes and http methods:

```bash
POST: /api/v1/register - register new user
POST: /api/v1/login - login user
POST: /api/v1/logout - Logout from current user
```

This route works based on parameters:

```bash
GET: /api/v1/posts - Display a listing of the post
GET: /api/v1/posts?page={pageNumber} - Paginate the listing of the post
GET: /api/v1/posts?filter={columnName} - Filter the listing of the post
GET: /api/v1/posts?field={columnName}&value={recordValue} - Sort the listing of the post
GET: /api/v1/posts?search={recordValues} - Search in the listing of the post
```

Or you can use everyone of these routes together, like this:

```bash
GET /api/v1/posts?page=1&field=id&value=desc&search=hello&filter=id
```

These routes need sending datas in body:

```bash
POST: /api/v1/posts - Store a newly created post in database
GET: /api/v1/posts/{id} - Display the specified post
PUT: /api/v1/posts/{id} - Update the specified post in database
DELETE: /api/v1/posts/{id} - Remove the specified post from database

POST: /api/v1/refresh - Exchange a refresh token for an access token when the access token has expired
```

All of these routes except register and login are provided with auth:api middleware which means you should send Authorization field in request header.

### Passport and Other Configuration

In VerifyCsrfToken middleware I set $except array to following routes to avoid sending csrf-token in the body like this:

```bash
protected $except = [
    'api/v1/posts',
    'api/v1/posts/*',
    'api/v1/register',
    'api/v1/login',
    'api/v1/refresh',
    'api/v1/logout',
];
```

I added tokens lifetime in boot method of AuthServiceProvider like this:

```bash
Passport::tokensExpireIn(now()->addHours(1));

Passport::refreshTokensExpireIn(now()->addMonths(1));

Passport::personalAccessTokensExpireIn(now()->addHours(1));
```

Then I added throttle middleware in route to provide simple rate limiting like these:

```bash
Route::group(['middleware' => 'throttle:100,1'], function() {

Route::get('posts', ...)->middleware('throttle:100,1');
```
Which means you can send 100 request per minute and after that you should stay till 1 minute to re-send you requests.

Also you can change your User class to other directory or rename it or etc by changing 'model' in users providers in auth.php config file:

```bash
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => Directory\User::class,
        ],
```

and I divided anything like Controller and Model and Request to the specific folder like Post and User and also use try-catch to handle some errors.

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## Alternatives
Also I used [laravel-modules](https://github.com/nWidart/laravel-modules) for creating modular project, and [Backend with Laravel and Passport](https://www.youtube.com/watch?v=StFvAYmg04o&t=799s) video.

## License
[MIT](https://choosealicense.com/licenses/mit/)