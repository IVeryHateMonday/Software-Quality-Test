<?php

namespace Entity;

class Book
{
    public string $title;
    public string $author;
    public float $price;
    public int $quantity;

    public int $ISBN;

    public function __construct(string $title, string $author, float $price, int $ISBN)
    {
        $this->title = $title;
        $this->author = $author;
        $this->price = $price;
        $this->ISBN = $ISBN;
    }
}
