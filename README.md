# User Module

This module is a skeleton to handle with users in your API with things generally required.
It's has endpoints to register, auth (default and social), password resets and confirmation email.

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

More details on [Docs](https://app.swaggerhub.com/apis/sfelix-martins/LaravelRobustAPI/1.0.0)

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

The module must be on `Modules\User` folder on root of your project

- Publish the module migrations:

```console
$ php artisan module:publish-migration User
```

- And run migrations:

```console
$ php artisan migrate
```

You can see the tables structure on `Modules\User\Database\Migrations`
