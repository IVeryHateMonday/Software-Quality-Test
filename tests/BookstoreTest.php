<?php

use PHPUnit\Framework\TestCase;
use Entity\Bookstore;
use Entity\Book;
use Services\ISBNService;
use ErrorHandler\BookstoreException;

class BookstoreTest extends TestCase
{
    protected Bookstore $store;

    protected function setUp(): void
    {
        $isbnService = new ISBNService();
        $this->store = new Bookstore($isbnService);
    }

    public function testAddBookWithQuantity(): void
    {
        $result = $this->store->addBookWithQuantity("Book1", "Author1", 10.0, 3);
        $this->assertEquals("Книга успішно додана (3 примірників)", $result);
        $this->assertCount(3, $this->store->books);

        // Перевіряємо, що ISBN унікальні
        $isbns = array_map(fn($book) => $book->ISBN, $this->store->books);
        $this->assertCount(3, array_unique($isbns));
    }

    public function testSearchByISBN(): void
    {
        $this->store->addBookWithQuantity("Book2", "Author2", 5.0, 1);
        $book = $this->store->books[0];

        $found = $this->store->searchByISBN($book->ISBN);
        $this->assertInstanceOf(Book::class, $found);
        $this->assertEquals($book, $found);

        $notFound = $this->store->searchByISBN(9999999999);
        $this->assertEquals("Книга не знайдена", $notFound);
    }

    public function testPurchaseBookCount(): void
    {
        $this->store->addBookWithQuantity("Book3", "Author3", 7.0, 5);

        // Купуємо 2 книги
        $result = $this->store->purchaseBookCount("Book3", "Author3", 2);
        $this->assertEquals("Покупка успішна", $result);
        $this->assertCount(3, $this->store->books);

        // Недостатньо примірників
        $result = $this->store->purchaseBookCount("Book3", "Author3", 5);
        $this->assertEquals("Недостатньо примірників", $result);

        // Купуємо всі залишки
        $result = $this->store->purchaseBookCount("Book3", "Author3", 3);
        $this->assertEquals("Покупка успішна", $result);
        $this->assertCount(0, $this->store->books);
    }

    public function testRemoveBook(): void
    {
        $this->store->addBook("Book4", "Author4", 12.0, 12345);
        $result = $this->store->removeBook("Book4", "Author4", 12.0, 12345);
        $this->assertEquals("Ok", $result);
        $this->assertCount(0, $this->store->books);

        $result = $this->store->removeBook("Book4", "Author4", 12.0, 12345);
        $this->assertEquals("Книга не знайдена", $result);
    }

    public function testSearchBookByTitle(): void
    {
        $this->store->addBookWithQuantity("PHP Basics", "Author5", 20, 2);

        $results = $this->store->searchBook("PHP");
        $this->assertIsArray($results);
        $this->assertCount(2, $results);

        $notFound = $this->store->searchBook("Python");
        $this->assertEquals("Не знайдено книгу", $notFound);
    }
}
