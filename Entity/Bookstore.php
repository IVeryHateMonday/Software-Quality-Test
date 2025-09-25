<?php

namespace Entity;

class Bookstore
{
    /** @var Book[] */
    public array $books = [];

    public function addBook(string $title, string $author, float $price, int $quantity): void
    {
        foreach ($this->books as $book) {
            if ($book->title === $title && $book->author === $author) {
                $book->quantity += $quantity;
                return;
            }
        }
        $this->books[] = new Book($title, $author, $price, $quantity);
    }

    public function removeBook(string $title, string $author): void
    {
        $this->books = array_filter($this->books, function ($book) use ($title, $author) {
            return !($book->title === $title && $book->author === $author);
        });
        $this->books = array_values($this->books);
    }

    public function searchBook(string $title): ?Book
    {
        foreach ($this->books as $book) {
            if (strcasecmp($book->title, $title) === 0) {
                return $book;
            }
        }
        return null;
    }

    public function purchaseBook(string $title, int $quantity): float
    {
        $book = $this->searchBook($title);
        if (!$book) {
            throw new \Exception("Книга '$title' не знайдена!");
        }
        if ($book->quantity < $quantity) {
            throw new \Exception("Недостатньо примірників '$title'. Є лише {$book->quantity} шт.");
        }

        $book->quantity -= $quantity;
        return $book->price * $quantity;
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
