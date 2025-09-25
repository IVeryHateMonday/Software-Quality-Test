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

    public function testAddBook(): void
    {
        $this->store->addBook("Book A", "Author A", 10.0, 5);
        $this->assertCount(1, $this->store->books);
        $this->assertEquals(5, $this->store->books[0]->quantity);

        // Додавання тієї ж книги має збільшити кількість, а не створити нову
        $this->store->addBook("Book A", "Author A", 10.0, 3);
        $this->assertCount(1, $this->store->books);
        $this->assertEquals(8, $this->store->books[0]->quantity);
    }

    public function testRemoveBook(): void
    {
        $this->store->addBook("Book A", "Author A", 10.0, 5);
        $this->store->removeBook("Book A", "Author A");
        $this->assertCount(0, $this->store->books);

        // Видалення неіснуючої книги не повинно викликати помилку
        $this->store->removeBook("NonExistent", "Author X");
        $this->assertCount(0, $this->store->books);
    }

    public function testSearchBook(): void
    {
        $this->store->addBook("Book A", "Author A", 10.0, 5);

        $found = $this->store->searchBook("Book A");
        $this->assertInstanceOf(Book::class, $found);
        $this->assertEquals("Author A", $found->author);

        // Пошук неіснуючої книги повертає null
        $notFound = $this->store->searchBook("NonExistent");
        $this->assertNull($notFound);
    }

    public function testPurchaseBook(): void
    {
        $this->store->addBook("Book A", "Author A", 10.0, 5);

        // Купівля частини книг
        $total = $this->store->purchaseBook("Book A", 3);
        $this->assertEquals(30.0, $total);
        $this->assertEquals(2, $this->store->books[0]->quantity);

        // Купівля більшої кількості, ніж є
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Недостатньо примірників 'Book A'");
        $this->store->purchaseBook("Book A", 5);

        // Купівля неіснуючої книги
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Книга 'Book B' не знайдена!");
        $this->store->purchaseBook("Book B", 1);
    }

    public function testInventoryValue(): void
    {
        $this->store->addBook("Book A", "Author A", 10.0, 2); // 20
        $this->store->addBook("Book B", "Author B", 15.0, 3); // 45
        $this->assertEquals(65.0, $this->store->inventoryValue());
    }
}
