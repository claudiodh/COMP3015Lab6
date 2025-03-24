<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Article;

class ArticleApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_the_15_most_recent_articles()
    {
        // Create 20 fake articles
        Article::factory()->count(20)->create();

        // Hit the API
        $response = $this->getJson('/api/articles');

        // Assert status and count
        $response->assertStatus(200)
            ->assertJsonCount(15);
    }

    public function test_it_creates_a_new_article()
    {
        $payload = [
            'title' => 'How to tame a Laravel beast',
            'url' => 'https://laravel.com/docs',
        ];

        $response = $this->postJson('/api/articles', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'How to tame a Laravel beast']);

        $this->assertDatabaseHas('articles', $payload);
    }

    public function test_it_returns_a_specific_article_and_increments_views()
    {
        $article = Article::factory()->create([
            'title' => 'View me!',
            'url' => 'https://example.com',
            'views' => 0,
        ]);

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'View me!']);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'views' => 1, // View count should have incremented
        ]);
    }

    public function test_it_updates_an_existing_article()
    {
        $article = Article::factory()->create([
            'title' => 'Original Title',
            'url' => 'https://original.com',
        ]);

        $payload = [
            'title' => 'Updated Title',
            'url' => 'https://updated.com',
        ];

        $response = $this->putJson("/api/articles/{$article->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title']);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
            'url' => 'https://updated.com',
        ]);
    }

    public function test_it_deletes_an_article()
    {
        $article = Article::factory()->create();

        $response = $this->deleteJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Article deleted']);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
        ]);
    }

}
