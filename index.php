<?php
require __DIR__ . '/vendor/autoload.php';

use Entity\Bookstore;
use Entity\Book;

$store = new Bookstore();

$store->addBook("The Great Gatsby", "F. Scott Fitzgerald", 10.99, 5);
$store->addBook("Test Book", "Author Test", 18.99, 2);

$store->showInventory();

$found = $store->searchBook("The Great Gatsby");
if ($found) {
    echo "\nЗнайдено книгу: {$found->title} — {$found->author} | {$found->price} грн | {$found->quantity} шт.\n";
}

try {
    $cost = $store->purchaseBook("Test Book", 1);
    echo "\nВартість покупки: $cost грн\n";
} catch (\Exception $e) {
    echo "Помилка: " . $e->getMessage() . "\n";
}

$store->removeBook("The Great Gatsby", "F. Scott Fitzgerald");

echo "\nІнвентар після видалення:\n";

echo "\nЗагальна вартість інвентарю: " . $store->inventoryValue() . " грн\n";
