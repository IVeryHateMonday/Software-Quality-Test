<?php

use PHPUnit\Framework\TestCase;
use Entity\Bookstore;
use Entity\Book;

class BookstoreTest extends TestCase
{
    private Bookstore $store;

    protected function setUp(): void
    {
        $this->store = new Bookstore();
    }

    // --- Тести додавання книг ---
    public function testAddBook(): void
    {
        $result = $this->store->addBook('Test','Test',10,2);
        $this->assertEquals('Ok', $result);
    }

    public function testAddSameBook(): void
    {
        $this->store->addBook('Test','Test',10,2);
        $result = $this->store->addBook('Test','Test',10,3);
        $this->assertCount(1, $this->store->books);
        $this->assertEquals(5, $this->store->books[0]->quantity);
        $this->assertEquals("Ok", $result);
    }

    public function testAddWrongBookByPrice(): void
    {
        $result = $this->store->addBook("Test", "Author", -10, 5);
        $this->assertEquals("Ціна повинна бути більшою за 0", $result);
    }

    public function testAddWrongBookByQuantity(): void
    {
        $result = $this->store->addBook("Test", "Author", 10, -5);
        $this->assertEquals("Кількість не може бути від’ємною", $result);
    }

    public function testAddWrongBookByEmptyTitle(): void
    {
        $result = $this->store->addBook("", "Author", 10, 5);
        $this->assertEquals("Назва або автор не можуть бути порожніми", $result);
    }

    public function testAddWrongBookByEmptyAuthor(): void
    {
        $result = $this->store->addBook("Test", "", 10, 5);
        $this->assertEquals("Назва або автор не можуть бути порожніми", $result);
    }

    // --- Тести видалення книг ---
    public function testRemoveBook(): void
    {
        $this->store->addBook("Test", "Author", 10, 5);
        $result = $this->store->removeBook("Test", "Author", 10);
        $this->assertEquals(4, $this->store->books[0]->quantity);
        $this->assertEquals("Ok", $result);
    }

    public function testRemoveBookUntilEmpty(): void
    {
        $this->store->addBook("Test", "Author", 10, 1);
        $result = $this->store->removeBook("Test", "Author", 10);
        $this->assertCount(0, $this->store->books);
        $this->assertEquals("Ok", $result);
    }

    public function testRemoveWrongBook(): void
    {
        $this->store->addBook("Test", "Author", 10, 5);
        $result = $this->store->removeBook("Test", "WrongAuthor", 10);
        $this->assertEquals("Книга не знайдена", $result);
    }

    // --- Тести покупки книг ---
    public function testPurchaseBook(): void
    {
        $this->store->addBook("Test", "Author", 10, 2);
        $total = $this->store->purchaseBook("Test", "Author", 10, 2);
        $this->assertEquals(20, $total);
    }

    public function testPurchaseBookExactQuantity(): void
    {
        $this->store->addBook("Test", "Author", 10, 2);
        $total = $this->store->purchaseBook("Test", "Author", 10, 2);
        $this->assertEquals(20, $total);
        $this->assertCount(0, $this->store->books);
    }

    public function testPurchaseBookNotEnoughQuantity(): void
    {
        $this->store->addBook("Test", "Author", 10, 1);
        $result = $this->store->purchaseBook("Test", "Author", 10, 2);
        $this->assertEquals("Недостатньо примірників", $result);
    }

    public function testPurchaseBookNotFound(): void
    {
        $result = $this->store->purchaseBook("NotExist", "Author", 10, 1);
        $this->assertEquals("Книга не знайдена", $result);
    }

    // --- Тести пошуку книг ---
    public function testSearchBookFound(): void
    {
        $this->store->addBook("Book1", "Author1", 10, 1);
        $this->store->addBook("Book2", "Author2", 15, 2);
        $results = $this->store->searchBook("Book1");
        $this->assertIsArray($results);
        $this->assertEquals("Book1", $results[0]->title);
    }

    public function testSearchBookNotFound(): void
    {
        $results = $this->store->searchBook("NotExist");
        $this->assertEquals("Не знайдено книгу", $results);
    }
}
