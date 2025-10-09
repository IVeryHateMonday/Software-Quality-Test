<?php

namespace Entity;

class Book
{
    public string $title;
    public string $author;
    public float $price;
    public int $quantity;

    public function __construct(string $title, string $author, float $price, int $quantity)
    {

        if (empty($title) || empty($author)) {
            return ("Назва або автор не можуть бути порожніми");
        }

        if ($price <= 0) {
            return ("Ціна повинна бути більшою за 0");
        }

        if ($quantity < 0) {
            return ("Кількість не може бути від’ємною");
        }

        $this->title = $title;
        $this->author = $author;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}
