<?php
namespace ErrorHandler;


namespace ErrorHandler;

class ISBNMessageHandler
{
    public static function handle(string $message, bool $log = true): void
    {
        if ($log) {
            error_log($message);
        }
        throw new BookstoreException($message);
    }
}