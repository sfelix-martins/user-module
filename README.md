# User Module

This module is a skeleton to handle with users in your API with things generally required.
It's has endpoints to register, auth (default and facebook), password resets and confirmation email.

The module was made to works with [Laravel Modules](https://github.com/nWidart/laravel-modules) package. A great package to organize your Laravel projects structure in `modules` instead keep your files into your `app` folder.

## Features

- Register Users
- OAuth 2 Authentication (Default and Facebook) using [Laravel Passport](https://laravel.com/docs/5.5/passport). 
- Reset Passwords
    - You can resets password on **browser** using routes:
        - GET  : `/password/reset` to show link request form
        - POST : `/password/email` to send reset link email
        - GET  : `/password/reset/{token}` to show reset form
        - POST : `/password/reset` to reset password
    - Or using the API endpoints
- Confirm Account

## Used Packages

- [Laravel Passport](https://laravel.com/docs/5.5/passport)
- [Laravel Socialite](https://github.com/laravel/socialite)
- [Passport Social Grant](https://github.com/adaojunior/passport-social-grant)
- [Laravel Cors](https://github.com/barryvdh/laravel-cors)

## Endpoints

- `POST`: /v1/users                     - Create users
- `POST`: /v1/oauth/token               - Default login and Facebook Login
- `GET` : /v1/users/{id}                - Get one user
- `POST`: /v1/password/email            - Sends password reset emails
- `POST`: /v1/password/reset            - Resets Passwords
- `GET` : /v1/account/verify/{token}    - Confirm email

More details on [Swagger Docs](https://app.swaggerhub.com/apis/sfelix-martins/LaravelRobustAPI/1.0.0#/)

## Events

- `Illuminate\Auth\Events\Registered` when user is registered
- `Illuminate\Auth\Events\PasswordReset` when resets password

## Configuring

#### Prerequisites

- A [laravel project](https://laravel.com/docs/5.5/)

#### Installing

- Install the user module on your project:

```console
$ composer require smartins/user-module
```

The module must be in `Modules\User` folder of your project

#### Database

**IMPORTANT:** Note that the migrations `create_users_table` and `create_password_resets_table` already exists in your project by default. To module works correctly delete the default migrations to create `users` and `password_resets_table` and use the migrations from User module. You can see the tables structure on `Modules\User\Database\Migrations`

- Publish the module migrations:

```console
$ php artisan module:publish-migration User
```

- And run migrations:

```console
$ php artisan migrate
```

The migrations to create `users`, `password_resets` and [Laravel Passport](https://laravel.com/docs/5.5/passport) migrations will be executed. Congratulations! You have the database structure to Register, Confirm Account, Login (OAuth2 - Default and Facebook) and Resets Password of your Users!

#### API Authentication (Laravel Passport)

The next step is configure the [Laravel Passport](https://laravel.com/docs/5.5/passport)

- Run the `passport:install` command to the encryption keys needed to generate secure access tokens. Copy the "password grant" client which will be used to generate access tokens:

```console
$ php artisan passport:install
```

- Next, you should call the `Passport::routes` method within the boot method of your `AuthServiceProvider`. This method will register the routes necessary to issue access tokens and revoke access tokens, clients, and personal access tokens:

```php
<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
```

- Finally, in your config/auth.php configuration file, you should set the driver option of the api authentication guard to passport. This will instruct your application to use Passport's TokenGuard when authenticating incoming API requests:

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```

After make the Laravel Passport configuration your Laravel Project must to be ready to authenticate users!

#### Social Authentication ([Laravel Socialite](https://github.com/laravel/socialite))

To use too facebook authentication you must make more some configurations.

- You will also need to add credentials for the OAuth services your application utilizes. These credentials should be placed in your `config/services.php` configuration file, and should use the key facebook, twitter, linkedin, google, github or bitbucket, depending on the providers your application requires. To use facebook place the following code:


```php
'facebook' => [
    'client_id' => env('FACEBOOK_ID'),
    'client_secret' => env('FACEBOOK_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT'),
],
```

- Set your facebook application keys on the `.env` file:

```env
FACEBOOK_ID=YourFacebookId
FACEBOOK_SECRET=YourFacebookSecret
FACEBOOK_REDIRECT=YourFacebookRedirectUrl
```

Well, it's all the required configurations to add support to social login to your API!
To add more social providers you just need update the class `Modules\User\Services\SocialUserResolver.php` setting your another providers. More details on [Passport Social Grant](https://github.com/adaojunior/passport-social-grant) package.

#### Resets Password and Email Confirmation

**Email**

To resets password and email confirmation works correctly you need configure the `sending email` and `queue` of your Laravel API.
Don't worry, it's pretty easy!

- In your `.env` file set your email configurations. You can use [mailtrap](mailtrap.io) on development.

```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

**OBS:** More instructions to configure the sending email in your laravel application on [Laravel Docs](https://laravel.com/docs/5.5/mail)

**Queue**

For me, the easy way to configure `Queues` on Laravel is using the `database` driver. 
Basically you just need prepare your database and... it's ready!

- In order to use the database queue driver, you will need a `database` table to hold the jobs. To generate a migration that creates this table, run the `queue:table` Artisan command. Once the migration has been created, you may migrate your database using the migrate command:

```console
$ php artisan queue:table

$ php artisan migrate

```

- Now you need only start a process to watch your queue. You can use the `queue:work` Artisan command:

```
$ php artisan queue:work
```

- Or configure the [Supervisor](https://laravel.com/docs/5.5/queues#supervisor-configuration)

## Use Sample

You can see a skeleton project with User module configured. Know the [Laravel Robust](https://github.com/sfelix-martins/laravel-robust)!
