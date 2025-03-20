<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

use App\Handler\Book\Admin\CreateBook;
use App\Handler\Book\Admin\DeleteBook;
use App\Handler\Book\Admin\EditBook;
use App\Handler\Book\Admin\UploadBookImage;
use App\Handler\Book\BorrowBook;
use App\Handler\Book\FindAllBooks;
use App\Handler\Book\FindBook;
use App\Handler\Book\ReturnBook;
use App\Handler\User\Admin\ChangeUserRole;
use App\Handler\User\Admin\DeleteUser;
use App\Handler\User\Auth\Login;
use App\Handler\User\Register\CompleteSignup;
use App\Handler\User\Register\CreateUser;
use App\Handler\User\Register\SendVerificationCode;
use App\Handler\User\Register\VerifyVerificationCode;
use App\Handler\User\UpdateProfile;
use App\Handler\User\UploadProfileImage;
use App\Middleware\Auth\AdminOnly;
use App\Middleware\Auth\GoogleCallback;
use App\Middleware\Auth\GoogleLogin;
use App\Middleware\Auth\GoogleOAuth\CallGoogleOAuthAPI;
use App\Middleware\Auth\GoogleOAuth\GoogleOAuthCallback;
use App\Middleware\Auth\GoogleOAuth\GoogleOAuthUserVerification;
use App\Middleware\Auth\GoogleOAuth\LoginWithGoogle;
use App\Middleware\Auth\GoogleOAuth\RegisterWithGoogle;
use App\Middleware\Auth\ValidateJWT;
use App\Middleware\Fetch\FetchBook;
use App\Middleware\Fetch\FetchUserFromRouteParam;
use App\Middleware\Validate\ValidBorrow;
use App\Middleware\Validate\VerifyCreateBookData;
use App\Middleware\Validate\VerifyEditBookDetails;
use App\Middleware\Validate\ValidImage;
use App\Middleware\Validate\VerifyLoginDetails;
use App\Middleware\Validate\ValidReturn;
use App\Middleware\Validate\ValidRole;
use App\Middleware\Validate\VerifySignupDetails;
use App\Middleware\Validate\VerifyUpdateProfileDetails;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/{id:\d+}', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->post('/users/signup', [
        VerifySignupDetails::class,
        CreateUser::class,
        SendVerificationCode::class,
    ], 'user.signup');

    $app->patch('/users/verify', [
        VerifyVerificationCode::class,
        CompleteSignup::class
    ], 'user.verify-email');


    $app->get('/users/signup/google', [
        CallGoogleOAuthAPI::class
    ], 'user.signup.google-oauth');

    $app->get('/google/callback', [
        GoogleOAuthCallback::class,
        GoogleOAuthUserVerification::class,
        Login::class
    ], 'user.google-oauth.callback');

    $app->get('/users/register/google', [
        RegisterWithGoogle::class,
        Login::class
    ], 'user.google.register');


    $app->post('/users/login', [
        VerifyLoginDetails::class,
        Login::class
    ], 'user.login');

    $app->patch('/users/update', [
        ValidateJWT::class,
        VerifyUpdateProfileDetails::class,
        UpdateProfile::class
    ], 'user.update');

    $app->post('/users/image',[
        ValidateJWT::class,
        ValidImage::class,
        UploadProfileImage::class
    ], 'user.upload-image');

    $app->patch('/users/{id}/role', [
        ValidateJWT::class,
        AdminOnly::class,
        ValidRole::class,
        FetchUserFromRouteParam::class,
        ChangeUserRole::class,
    ], 'user.admin.change-role');

    $app->delete('/users/{id}', [
        ValidateJWT::class,
        AdminOnly::class,
        FetchUserFromRouteParam::class,
        DeleteUser::class
    ], 'user.delete');


    $app->post('/books',[
        ValidateJWT::class,
        AdminOnly::class,
        VerifyCreateBookData::class,
        CreateBook::class
    ], 'book.create');

    $app->get('/books', [
        ValidateJWT::class,
        FindAllBooks::class
    ], 'book.findAll');
    
    $app->get('/books/{book_id}',[
        ValidateJWT::class,
        FetchBook::class,
        FindBook::class
    ], 'book.find');

    $app->patch('/books/{book_id}/details',[
        ValidateJWT::class,
        AdminOnly::class,
        FetchBook::class,
        VerifyEditBookDetails::class,
        EditBook::class,
    ], 'book.edit');

    $app->post('/books/{book_id}/image',[
        ValidateJWT::class,
        AdminOnly::class,
        FetchBook::class,
        ValidImage::class,
        UploadBookImage::class,
    ], 'book.upload-image');

    $app->delete('/books/{book_id}', [
        ValidateJWT::class,
        AdminOnly::class,
        FetchBook::class,
        DeleteBook::class,
    ], 'book.delete');

    $app->post('/book/{book_id}/borrow', [
        ValidateJWT::class,
        FetchBook::class,
        ValidBorrow::class,
        BorrowBook::class
    ], 'book.borrow');

    $app->patch('/book/{book_id}/return', [
        ValidateJWT::class,
        FetchBook::class,
        ValidReturn::class,
        ReturnBook::class
    ], 'book.return');
};

// send grid || elastic mail    // the email services.


// Do oAuth and 2 factor authentication

// Send users automated email
// Chorn jobs


// Photo collage on Cloudinary.
// This one!