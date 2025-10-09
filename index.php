<?php
require __DIR__ . '/vendor/autoload.php';

use Entity\Bookstore;
use Entity\Book;

$store = new Bookstore();

while (true){
    echo "\nКоманди: add, remove, purchase, show, exit: \n";
    $command = readline('Введіть команду: ');
    switch (strtolower($command)) {
        case 'add':
            $title = readline("Назва книги: ");
            $author = readline("Автор: ");
            $price = (float) readline("Ціна: ");
            $quantity = (int) readline("Кількість: ");
            try {
                $store->addBook($title, $author, $price, $quantity);
                echo "Книга додана!\n";
            } catch (Exception $e) {
                echo "Помилка: " . $e->getMessage() . "\n";
            }
            break;
        case 'test':
            $title = "test";
            $author = "test";
            $price = 5;
            $quantity = 3;
            try {
                $store->addBook($title, $author, $price, $quantity);

                echo "Книга додана!\n";
            } catch (Exception $e) {
                echo "Помилка: " . $e->getMessage() . "\n";
            }
            break;
        case 'remove':
            $title = readline("Назва книги: ");
            $author = readline("Автор: ");
            $store->removeBook($title, $author);
            echo "Книга видалена!\n";
            break;

        case 'purchase':
            $title = readline("Назва книги: ");
            $quantity = (int) readline("Кількість: ");
            try {
                $total = $store->purchaseBook($title, $quantity);
                echo "Куплено на суму: $total грн\n";
            } catch (Exception $e) {
                echo "Помилка: " . $e->getMessage() . "\n";
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
//$store->addBook("The Great Gatsby", "F. Scott Fitzgerald", 10.99, 5);
//$store->addBook("Test Book", "Author Test", 18.99, 2);
//
//$store->showInventory();
//
//$found = $store->searchBook("The Great Gatsby");
//if ($found) {
//    echo "\nЗнайдено книгу: {$found->title} — {$found->author} | {$found->price} грн | {$found->quantity} шт.\n";
//}
//
//try {
//    $cost = $store->purchaseBook("Test Book", 1);
//    echo "\nВартість покупки: $cost грн\n";
//} catch (\Exception $e) {
//    echo "Помилка: " . $e->getMessage() . "\n";
//}
//
//$store->removeBook("The Great Gatsby", "F. Scott Fitzgerald");
//
//echo "\nІнвентар після видалення:\n";
//
//echo "\nЗагальна вартість інвентарю: " . $store->inventoryValue() . " грн\n";
