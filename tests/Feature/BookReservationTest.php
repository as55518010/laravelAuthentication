<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_the_lirary()
    {
        $this->withoutExceptionHandling();

        $respose =  $this->post('/books', [
            'title' => 'cool Book Title',
            'author' => 'Victor'
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $respose->assertRedirect('/books/' . $book->id);
    }
    /** @test */
    public function a_title_is_required()
    {
        // $this->withoutExceptionHandling();

        $respose =  $this->post('/books', [
            'title' => '',
            'author' => 'Victor'
        ]);

        $respose->assertSessionHasErrors('title');
    }
    /** @test */
    public function a_author_is_required()
    {
        // $this->withoutExceptionHandling();

        $respose =  $this->post('/books', [
            'title' => 'Cool Title',
            'author' => ''
        ]);

        $respose->assertSessionHasErrors('author');
    }
    /** @test */
    public function a_book_can_be_update()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'cool Book Title',
            'author' => 'Victor'
        ]);

        $book = Book::first();

        $respose = $this->patch('/books/' . $book->id, [
            'title' => 'New Title',
            'author' => 'New Author'
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);

        $respose->assertRedirect('/books/' . $book->id);
    }
    /** @test */
    public function a_book_can_be_delete()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'cool Book Title',
            'author' => 'Victor'
        ]);
        $this->assertCount(1, Book::all());

        $book = Book::first();

        $respose = $this->delete('/books/' . $book->id);

        $this->assertCount(0, Book::all());

        $respose->assertRedirect('/books');
    }
}
