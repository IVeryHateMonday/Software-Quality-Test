<?php
require __DIR__ . '/vendor/autoload.php';

use Entity\Bookstore;
use ErrorHandler\BookstoreException;

$store = new Bookstore();
//$store->addBook("test","test",-5,2);
print_r ($store->books);
while (true){
    echo "\nКоманди: add, remove, purchase, show, exit: \n";
    $command = readline('Введіть команду: ');
    switch (strtolower($command)) {
        case 'add':
            $title = readline("Назва книги: ");
            $author = readline("Автор: ");
            $price = (float) readline("Ціна: ");
            $quantity = (int) readline("Кількість: ");
            $ISBN = readline("ISBN: ");

            try {
                $store->addBook($title, $author, $price, $quantity, $ISBN);
                echo "Книга додана!\n";
            } catch (BookstoreException $e) {
                echo "Помилка: " . $e->getMessage() . "\n";
            }
            break;
        case 'test':
            $title = "test";
            $author = "test";
            $price = 5;
            $quantity = 3;
            try {
                $store->addBook($title, $author, $price, $quantity, $ISBN);
                echo "Книга додана!\n";
            } catch (BookstoreException $e) {
                echo "Помилка: " . $e->getMessage() . "\n";
            } catch (\Exception $e) {
                echo "Системна помилка: " . $e->getMessage() . "\n";
            }
            break;
        case 'remove':
            $title = readline("Назва книги: ");
            $author = readline("Автор: ");
            $price= readline("Ціна: ");

            echo $store->removeBook($title, $author,$price);
            break;

        case 'purchase':
            $title = readline("Назва книги: ");
            $author = readline("Автор: ");
            $price= readline("Ціна: ");
            $quantity = readline("Кількість: ");
            print_r($store->purchaseBook($title,$author,$price,$quantity));
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
