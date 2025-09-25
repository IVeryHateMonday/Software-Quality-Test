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
        $this->title = $title;
        $this->author = $author;
        $this->price = $price;
        $this->quantity = $quantity;
    }
}
