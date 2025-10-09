<?php

namespace Entity;

class Bookstore
{
    /** @var Book[] */
    public array $books = [];

    public function addBook(string $title, string $author, float $price, int $quantity): void
    {
        if ($price < 0) {
            throw new \InvalidArgumentException("Ціна не може бути від’ємною!");
        }
        if ($quantity < 0) {
            throw new \InvalidArgumentException("Кількість не може бути від’ємною!");
        }

        foreach ($this->books as $book) {
            if ($book->title === $title && $book->author === $author) {
                $book->quantity += $quantity;
                return;
            }
        }
        $this->books[] = new Book($title, $author, $price, $quantity);
    }



    public function removeBook(string $title, string $author, ?int $quantity = null)
    {
        foreach ($this->books as $index => $book) {
            if ($book->title === $title && $book->author === $author) {
                if ($quantity === null || $quantity >= $book->quantity) {
                    // повне видалення
                    unset($this->books[$index]);
                } else {
                    // зменшення кількості
                    $book->quantity -= $quantity;
                }
                $this->books = array_values($this->books);
                return true;
            }
        }
        return false;
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
