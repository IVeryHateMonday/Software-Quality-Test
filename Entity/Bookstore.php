<?php

namespace Entity;

class Bookstore
{
    /** @var Book[] */
    public array $books = [];

    public function addBook(string $title, string $author, float $price, int $quantity)
    {
        if (empty($title) || empty($author)) {
            return "Назва або автор не можуть бути порожніми";
        }

        if ($price <= 0) {
            return "Ціна повинна бути більшою за 0";
        }

        if ($quantity < 0) {
            return "Кількість не може бути від’ємною";
        }

        $book = new Book($title, $author, $price, $quantity);

        foreach ($this->books as $storeBook) {
            if ($storeBook->title === $book->title &&
                $storeBook->author === $book->author &&
                $storeBook->price === $book->price) {
                $storeBook->quantity += $book->quantity;
                return "Ok";
            }
        }

        $this->books[] = $book;
        return "Ok";
    }

    public function removeBook(string $title, string $author, float $price)
    {
        foreach ($this->books as $index => $book) {
            if ($book->title === $title && $book->author === $author && $book->price === $price) {
                if ($book->quantity > 1) {
                    $book->quantity--;
                } else {
                    unset($this->books[$index]);
                    $this->books = array_values($this->books);
                }
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

    public function purchaseBook(string $title, string $author, float $price, int $quantity)
    {
        if (empty($title) || empty($author)) {
            return "Назва або автор не можуть бути порожніми";
        }

        if ($price <= 0) {
            return "Ціна повинна бути більшою за 0";
        }

        if ($quantity < 0) {
            return "Кількість не може бути від’ємною";
        }

        foreach ($this->books as $index => $book) {
            if ($book->title === $title && $book->author === $author && $book->price === $price) {
                if ($book->quantity >= $quantity) {
                    $book->quantity -= $quantity;

                    if ($book->quantity === 0) {
                        unset($this->books[$index]);
                        $this->books = array_values($this->books);
                    }

                    return $price * $quantity; // повертаємо суму покупки
                } else {
                    return "Недостатньо примірників";
                }
            }
        }

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
            echo "{$book->title} — {$book->author} | {$book->price} грн | {$book->quantity} шт.\n";
        }
    }
}
