# User Module

The module has an skeleton user flow. 
It's has routes to register, auth (default and social), password resets and confirmation email.

The authentication on API is using [Laravel Passport](https://laravel.com/docs/5.5/passport). 

## Features

- Register Users
- OAuth 2 Authentication (Default and Facebook)
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

- Install package from [joshbrw/laravel-module-installer](https://github.com/joshbrw/laravel-module-installer) to install this module on folder `Modules` correctly

```console
composer require joshbrw/laravel-module-installer
```

- Install the user module package:

```console
composer require smartins/user-module
```

- Publish the module migrations:

```console
php artisan module:publish-migration User
```

- And run migrations:

```console
php artisan migrate
```

- Or run the migrations directly on model:

```console
php artisan module:migrate User
```

You can see the tables structure on `Modules\User\Database\Migrations`
