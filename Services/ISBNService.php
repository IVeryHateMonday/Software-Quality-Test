<?php

namespace Services;
use ErrorHandler\ISBNMessageHandler;
use ErrorHandler\UserErrorMessages;
use Entity\Book;

class ISBNService
{
    public function validateFormat($ISBN)
    {
        if (!ctype_digit((string)$ISBN)) {
            ISBNMessageHandler::handle(UserErrorMessages::ISBN_INVALID);
        }
    }
    public function searchByISBN($ISBN, array $books)
    {
        $ISBN = (int)$ISBN;
        foreach ($books as $book) {
            if ($book->ISBN === $ISBN) {
                return $book; // повертаємо об'єкт Book
            }
        }
        return null; // або кинути виняток, якщо потрібно
    }
    public function generateISBN()
    {
        return random_int(1000000000, 9999999999);
    }
}