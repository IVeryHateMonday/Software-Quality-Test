<?php

namespace Entity;



use ErrorHandler\ISBNMessageHandler;
use ErrorHandler\UserErrorMessages;

class Bookstore
{
    /** @var Book[] */
    public array $books = [];
    protected $isbnService;

    public function __construct($isbnService)
    {
        $this->isbnService = $isbnService;
    }
    public function searchByISBN(int|string $ISBN)
    {
        return $this->isbnService->searchByISBN($ISBN, $this->books);
    }
    public function addBookWithQuantity(string $title, string $author, float $price, int $quantity)
    {
        // 1️⃣ Перевірка на порожні значення
        if (empty($title) || empty($author)) {
            ISBNMessageHandler::handle(UserErrorMessages::EMPTY_TITLE_OR_AUTHOR);
        }

        // 2️⃣ Перевірка ціни
        if ($price <= 0) {
            ISBNMessageHandler::handle(UserErrorMessages::INVALID_PRICE);
        }

        // 3️⃣ Перевірка кількості
        if ($quantity <= 0) {
            ISBNMessageHandler::handle(UserErrorMessages::INVALID_QUANTITY);
        }

        // 4️⃣ Генеруємо і додаємо унікальні книги
        for ($i = 0; $i < $quantity; $i++) {
            $ISBN = (int)$this->isbnService->generateISBN();
            $this->isbnService->validateFormat($ISBN);

            // Перевіряємо, щоб ISBN не дублювався у магазині
            foreach ($this->books as $book) {
                if ($book->ISBN === $ISBN) {
                    ISBNMessageHandler::handle(UserErrorMessages::ISBN_ALREADY_EXISTS);
                }
            }

            // Створюємо новий екземпляр
            $book = new Book($title, $author, $price, $ISBN);
            $this->books[] = $book;
        }

        return "Книга успішно додана ($quantity примірників)";
    }
    public function addBook(string $title, string $author, float $price, string|int $ISBN)
    {
        if (empty($title) || empty($author)) {
            ISBNMessageHandler::handle(UserErrorMessages::EMPTY_TITLE_OR_AUTHOR);
        }

        if ($price <= 0) {
            ISBNMessageHandler::handle(UserErrorMessages::INVALID_PRICE);
        }



        if (!ctype_digit((string)$ISBN)) {
            ISBNMessageHandler::handle(UserErrorMessages::ISBN_INVALID);
        }

        $ISBN = (int)$ISBN;

        foreach ($this->books as $bookStore){
            if ($bookStore->ISBN ===$ISBN){
                ISBNMessageHandler::handle(UserErrorMessages::ISBN_ALREADY_EXISTS);
            }
        }

        $book = new Book($title, $author, $price, $ISBN);

//        foreach ($this->books as $storeBook) {
//            if ($storeBook->title === $book->title &&
//                $storeBook->author === $book->author &&
//                $storeBook->price === $book->price &&
//                $storeBook->ISBN === $book->ISBN
//            ) {
//
//                return "Ok";
//            }
//        }


        $this->books[] = $book;
        return "Ok";
    }


    public function removeBook(string $title, string $author, $price, int|string $ISBN)
    {
        // Приведення типів
        $price = (float)$price;
        $ISBN = (int)$ISBN;

        foreach ($this->books as $index => $book) {
            if (
                $book->title == $title &&
                $book->author == $author &&
                $book->price == $price &&
                $book->ISBN == $ISBN
            ) {
                unset($this->books[$index]);
                $this->books = array_values($this->books);
                return "Ok";
            }
        }

        return "Книга не знайдена";
    }


    public function searchBook(string $title)
    {
        $results = [];

        foreach ($this->books as $book) {
            if (stripos($book->title, $title) !== false) {
                $results[] = $book;
            }
        }

        return empty($results) ? "Не знайдено книгу" : $results;
    }
    public function purchaseBookCount(string $title, string $author, int $count)
    {
        if (empty($title) || empty($author)) {
            return "Назва або автор не можуть бути порожніми";
        }

        // Знаходимо всі книги з цією назвою й автором
        $matchedBooks = [];
        foreach ($this->books as $index => $book) {
            if ($book->title === $title && $book->author === $author) {
                $matchedBooks[] = $index;
            }
        }

        if (empty($matchedBooks)) {
            return "Книга не знайдена";
        }

        if (count($matchedBooks) < $count) {
            return "Недостатньо примірників";
        }

        // Видаляємо потрібну кількість з інвентарю
        for ($i = 0; $i < $count; $i++) {
            $indexToRemove = array_shift($matchedBooks);
            unset($this->books[$indexToRemove]);
        }

        // Перевпорядковуємо масив, щоб уникнути "дір"
        $this->books = array_values($this->books);

        return "Покупка успішна";
    }
    public function purchaseBook(string $title, string $author,$price, int|float $ISBN)
    {
        if (empty($title) || empty($author)) {
            return "Назва або автор не можуть бути порожніми";
        }

        foreach ($this->books as $index => $book) {
            if ($book->price==$price && $book->title == $title && $book->author == $author && $book->ISBN == $ISBN) {

                return "Куплена книга";
            }
        }

        // якщо жодна книга не підійшла
        return "Книга не знайдена";
    }

    public function inventoryValue(): float
    {
        $total = 0;
        foreach ($this->books as $book) {
            $total += $book->price * $book->quantity;
        }
        return $total;
    }

    public function showInventory(): void
    {
        echo "Інвентар магазину:\n";
        foreach ($this->books as $book) {
            echo "{$book->title} — {$book->author} | {$book->price} грн | {$book->ISBN} ISBN.\n";
        }
    }
}
