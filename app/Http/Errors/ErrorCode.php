<?php


namespace App\Http\Errors;


class ErrorCode
{
    public const INVALID_FIELD = 1;
    public const UNAUTHORIZED = 2;
    public const SYSTEM_ERROR = 3;
    public const AUTH_ERROR = 4;
    public const ACCESS_DENIED = 5;
    public const UNIQUE_RESOURCE_CONFLICT = 6;
    public const RESOURCE_NOT_FOUND = 7;
    public const INVALID_ARGUMENT = 8;
    public const INVALID_TOKEN = 9;
    public const INVALID_RESET_CODE = 10;
    public const INVALID_PASSWORD_FORMAT = 11;
    public const INVALID_EMAIL_FORMAT = 12;
    public const INVALID_USERNAME_FORMAT = 13;
    public const EXPIRED_RESET_CODE = 14;
    public const EXPIRED_TOKEN = 15;
    public const EMPTY_CODE = 16;
    public const FILE_NOT_FOUND = 17;
    public const TOO_LARGE_FILE_SIZE = 18;
    public const REQUIRED_PARAMS_NOT_FOUND = 19;
    public const ALREADY_EXISTS = 20;
    public const ALREADY_REQUESTED = 21;
    public const NOT_ALLOWED = 22;
    public const PASSWORDS_MISMATCH = 23;
    public const FIELD_REQUIRED = 24;
    public const NOT_ENOUGH_BALANCE = 25;
    public const SUBSCRIPTION_EXPIRED = 26;
    public const EMAIL_NOT_VERIFIED = 27;
    public const PHONE_NOT_VERIFIED = 28;
    public const TOO_MANY_ATTEMPTS = 29;
}
