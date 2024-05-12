# Laravel JWT Authentication guard driver
Package adds new option for guard driver named `jwt`. 

This guard inherits existing class `Illuminate\Auth\TokenGuard` to avoid code duplication and uses [Firebase/php-jwt](https://github.com/firebase/php-jwt) for validation logic.

## Usage
1. In `composer.json` file add new entry:
```json
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/oidd/laravel-jwt-auth"
        }
    ],
```
2. Run `composer require b6dbde/laravel-jwt-auth:@dev`.
3. Run `php artisan vendor:publish --provider="LaravelJwtAuth\JwtServiceProvider"`, this will add new config file `config/jwt.php`.
4. Then you should add `AUTH_JWT_SECRET_KEY` entry into `.env` file and provide a sercret key.
5. In your `config/auth.php` config file, set guard driver to `jwt`, e.g.
```php
//...
'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],
```
6. To retrieve a user from JWT token, use standart `user()` method of `Illuminate\Http\Request` object
```php
Route::post('/example', function (Request $request) {
    return response()->json($request->user());
});
```
or 'validate()' method of Auth facade
```php
Route::post('/example', function (Request $request) {
    return response()->json(\Illuminate\Support\Facades\Auth::validate($request->all()));
});
```

## Config explanations
Package creates new `config/jwt.php` config file with a few entries. Let's look into them.
1. 'input_key' – a field in request input that contains jwt token. Default value is 'api_token'.
2. 'storage_key' – a field in user model (or just table) that contains user id. Default value is 'id'.
3. 'token_key' – a field in jwt payload that contains user id. Default value is 'user_id'.
4. 'secret' – a jwt secret key used for HS256 encryption and validation.
