# Laravel Authentication Scaffolding

This directory contains the authentication scaffolding for a Laravel application, including routes, controllers, and middleware configuration.

## Routes

The following routes are defined in `web.php` to handle authentication:

- **Guest Middleware Group:**
  - `GET /register` - Display the registration form.
  - `POST /register` - Handle the registration form submission.
  - `GET /login` - Display the login form.
  - `POST /login` - Handle the login form submission.

- **Auth Middleware Group:**
  - `GET /check` - Check the authentication status.
  - `DELETE /logout` - Handle the logout action.

- **Redirection:**
  - `GET /` - Redirect to `/check`.

### Route Definitions

```php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/check');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/check', [AuthController::class, 'check'])->name('check');
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
});
