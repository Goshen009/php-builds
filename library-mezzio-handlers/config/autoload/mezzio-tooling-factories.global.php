<?php

/**
 * This file generated by Mezzio\Tooling\Factory\ConfigInjector.
 *
 * Modifications should be kept at a minimum, and restricted to adding or
 * removing factory definitions; other dependency types may be overwritten
 * when regenerating this file via mezzio-tooling commands.
 */
 
declare(strict_types=1);

return [
    'dependencies' => [
        'factories' => [
            App\Handler\Book\Admin\CreateBook::class => App\Handler\Book\Admin\CreateBookFactory::class,
            App\Handler\Book\Admin\DeleteBook::class => App\Handler\Book\Admin\DeleteBookFactory::class,
            App\Handler\Book\Admin\EditBook::class => App\Handler\Book\Admin\EditBookFactory::class,
            App\Handler\Book\Admin\UploadBookImage::class => App\Handler\Book\Admin\UploadBookImageFactory::class,
            App\Handler\Book\BorrowBook::class => App\Handler\Book\BorrowBookFactory::class,
            App\Handler\Book\FindAllBooks::class => App\Handler\Book\FindAllBooksFactory::class,
            App\Handler\Book\FindBook::class => App\Handler\Book\FindBookFactory::class,
            App\Handler\Book\ReturnBook::class => App\Handler\Book\ReturnBookFactory::class,
            App\Handler\User\Admin\ChangeUserRole::class => App\Handler\User\Admin\ChangeUserRoleFactory::class,
            App\Handler\User\Admin\DeleteUser::class => App\Handler\User\Admin\DeleteUserFactory::class,
            App\Handler\User\Auth\Login::class => App\Handler\User\Auth\LoginFactory::class,
            App\Handler\User\Auth\Signup::class => App\Handler\User\Auth\SignupFactory::class,
            App\Handler\User\Register\CompleteSignup::class => App\Handler\User\Register\CompleteSignupFactory::class,
            App\Handler\User\Register\CreateUser::class => App\Handler\User\Register\CreateUserFactory::class,
            App\Handler\User\Register\SendVerificationCode::class => App\Handler\User\Register\SendVerificationCodeFactory::class,
            App\Handler\User\Register\VerifyVerificationCode::class => App\Handler\User\Register\VerifyVerificationCodeFactory::class,
            App\Handler\User\UpdateProfile::class => App\Handler\User\UpdateProfileFactory::class,
            App\Handler\User\UploadProfileImage::class => App\Handler\User\UploadProfileImageFactory::class,
            App\Middleware\Auth\AdminOnly::class => App\Middleware\Auth\AdminOnlyFactory::class,
            App\Middleware\Auth\GoogleOAuth\CallGoogleOAuthAPI::class => App\Middleware\Auth\GoogleOAuth\CallGoogleOAuthAPIFactory::class,
            App\Middleware\Auth\GoogleOAuth\GoogleOAuthCallback::class => App\Middleware\Auth\GoogleOAuth\GoogleOAuthCallbackFactory::class,
            App\Middleware\Auth\GoogleOAuth\GoogleOAuthUserVerification::class => App\Middleware\Auth\GoogleOAuth\GoogleOAuthUserVerificationFactory::class,
            App\Middleware\Auth\GoogleOAuth\RegisterWithGoogle::class => App\Middleware\Auth\GoogleOAuth\RegisterWithGoogleFactory::class,
            App\Middleware\Auth\ValidateJWT::class => App\Middleware\Auth\ValidateJWTFactory::class,
            App\Middleware\Fetch\FetchBook::class => App\Middleware\Fetch\FetchBookFactory::class,
            App\Middleware\Fetch\FetchUserFromRouteParam::class => App\Middleware\Fetch\FetchUserFromRouteParamFactory::class,
            App\Middleware\Validate\ValidBorrow::class => App\Middleware\Validate\ValidBorrowFactory::class,
            App\Middleware\Validate\ValidImage::class => App\Middleware\Validate\ValidImageFactory::class,
            App\Middleware\Validate\ValidReturn::class => App\Middleware\Validate\ValidReturnFactory::class,
            App\Middleware\Validate\ValidRole::class => App\Middleware\Validate\ValidRoleFactory::class,
            App\Middleware\Validate\VerifyCreateBookData::class => App\Middleware\Validate\VerifyCreateBookDataFactory::class,
            App\Middleware\Validate\VerifyEditBookDetails::class => App\Middleware\Validate\VerifyEditBookDetailsFactory::class,
            App\Middleware\Validate\VerifyLoginDetails::class => App\Middleware\Validate\VerifyLoginDetailsFactory::class,
            App\Middleware\Validate\VerifySignupDetails::class => App\Middleware\Validate\VerifySignupDetailsFactory::class,
            App\Middleware\Validate\VerifyUpdateProfileDetails::class => App\Middleware\Validate\VerifyUpdateProfileDetailsFactory::class,
        ],
    ],
];
