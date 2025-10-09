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

    // ===================== Основні тести =====================
    public function testAddBook(): void
    {
        $this->store->addBook("Book A", "Author A", 10.0, 5);
        $this->assertCount(1, $this->store->books);
        $this->assertEquals(5, $this->store->books[0]->quantity);

        $this->store->addBook("Book A", "Author A", 10.0, 3);
        $this->assertCount(1, $this->store->books);
        $this->assertEquals(8, $this->store->books[0]->quantity);

        $this->store->addBook("Book A", "Author A", 10.0, -3);
        $this->assertCount(1, $this->store->books);
        $this->assertEquals(5, $this->store->books[0]->quantity);

        $this->store->addBook("Book A", "Author A", -123, -3);
        $this->assertCount(1, $this->store->books);
        $this->assertEquals(2, $this->store->books[0]->quantity);

        $this->store->addBook("Book A", "Author A", 10.0, 3);
        $this->assertCount(1, $this->store->books);
        $this->assertEquals(5, $this->store->books[0]->quantity);

        $this->store->addBook("Book A", "Author A", "asdasd", 3);
        $this->assertCount(1, $this->store->books);
        $this->assertEquals(8, $this->store->books[0]->quantity);
    }

    public function testRemoveBook(): void
    {
        $this->store->addBook("Book A", "Author A", 10.0, 5);
        $this->store->removeBook("Book A", "Author A");
        $this->assertCount(0, $this->store->books);

        $this->store->removeBook("NonExistent", "Author X");
        $this->assertCount(0, $this->store->books);
    }

    public function testSearchBook(): void
    {
        $this->store->addBook("Book A", "Author A", 10.0, 5);

        $found = $this->store->searchBook("Book A");
        $this->assertInstanceOf(Book::class, $found);
        $this->assertEquals("Author A", $found->author);

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
    }

    public function testInventoryValue(): void
    {
        $this->store->addBook("Book A", "Author A", 10.0, 2); // 20
        $this->store->addBook("Book B", "Author B", 15.0, 3); // 45
        $this->assertEquals(65.0, $this->store->inventoryValue());
    }

    // ===================== Edge cases =====================
    public function testAddBookWithNegativeQuantityOrPrice(): void
    {
        // Негативна кількість
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Кількість не може бути від’ємною!");
        $this->store->addBook("Book X", "Author X", 50.0, -5);
    }

    public function testAddBookWithNegativePrice(): void
    {
        // Негативна ціна
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Ціна не може бути від’ємною!");
        $this->store->addBook("Book Y", "Author Y", -10.0, 3);
    }

    public function testPurchaseBookWithZeroStock(): void
    {
        $this->store->addBook("Book A", "Author A", 100.0, 0);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Недостатньо примірників 'Book A'");
        $this->store->purchaseBook("Book A", 1);
    }

    public function testRemoveNonExistingBook(): void
    {
        $this->store->removeBook("NonExistent", "Unknown");
        $this->assertCount(0, $this->store->books);
    }

    public function testSearchBookWithEmptyOrNullTitle(): void
    {
        $this->store->addBook("Book A", "Author A", 20.0, 2);

        // Порожній рядок
        $this->assertNull($this->store->searchBook(""));

        // null приводимо до string
        $this->assertNull($this->store->searchBook((string)null));
    }
}
