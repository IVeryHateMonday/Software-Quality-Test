<?php
require __DIR__ . '/vendor/autoload.php';

use Entity\Bookstore;
use ErrorHandler\BookstoreException;
use Services\ISBNService;

// Створюємо сервіс
$isbnService = new ISBNService();

// Передаємо його у Bookstore
$store = new Bookstore($isbnService);
//$store->addBook("test","test",-5,2);
print_r ($store->books);
while (true){
    echo "\nКоманди: add, remove, purchase, show, exit: \n";
    $command = readline('Введіть команду: ');
    switch (strtolower($command)) {
        case 'purchase count':
            $title = readline("Назва книги: ");
            $author = readline("Автор: ");
            $count = (int) readline("Скільки примірників купити: ");

            try {
                $result = $store->purchaseBookCount($title, $author, $count);
                echo $result . "\n";
            } catch (BookstoreException $e) {
                echo "Помилка: " . $e->getMessage() . "\n";
            }
            break;

        case 'add qua':
            $title = readline("Назва книги: ");
            $author = readline("Автор: ");
            $price = (float) readline("Ціна: ");
            $quantity = (int) readline("Кількість: ");
            try {
                $store->addBookWithQuantity($title, $author, $price, $quantity);
                echo "Книга додана!\n";
            } catch (BookstoreException $e) {
                echo "Помилка: " . $e->getMessage() . "\n";
            }
        break;
        case 'add':
            $title = readline("Назва книги: ");
            $author = readline("Автор: ");
            $price = (float) readline("Ціна: ");
//            $quantity = (int) readline("Кількість: ");
            $ISBN = readline("ISBN: ");

            try {
                $store->addBook($title, $author, $price, $ISBN);
                echo "Книга додана!\n";
            } catch (BookstoreException $e) {
                echo "Помилка: " . $e->getMessage() . "\n";
            }
            break;
        case 'test':
            $title = "test";
            $author = "test";
            $price = 5;

            try {
                $store->addBook($title, $author, $price, 23);
                $store->showInventory();
                echo "Книга додана!\n";
            } catch (BookstoreException $e) {
                echo "Помилка: " . $e->getMessage() . "\n";
            } catch (\Exception $e) {
                echo "Системна помилка: " . $e->getMessage() . "\n";
            }
            break;
        case 'find isbn':
            $ISBN= readline("ISBN: ");
            $book = $store->searchByISBN($ISBN);

            echo $book;

            break;

        case 'remove':
            $title = readline("Назва книги: ");
            $author = readline("Автор: ");
            $price= readline("Ціна: ");
            $ISBN= readline("ISBN: ");
            echo $store->removeBook($title, $author,$price,$ISBN);
            break;

        case 'purchase':
            $title = readline("Назва книги: ");
            $author = readline("Автор: ");
            $price= readline("Ціна: ");
            $ISBN= readline("ISBN: ");
            print_r($store->purchaseBook($title,$author,$price,$ISBN));
            break;
        case 'search':
            $title = readline("Назва книги: ");
            $book = $store->searchBook($title);

            if ($book === "Не знайдено книгу") {
                echo "Книга не знайдена\n";
            } else {
                echo "Назва: " . $book->title . " Автор: " . $book->author . " Ціна: " . $book->price . "\n";
            }
            break;
        case 'show':
            $store->showInventory();
            break;

        case 'exit':
            echo "Вихід...\n";
            exit;

        default:
            echo "Невідома команда.\n";
    }
}
