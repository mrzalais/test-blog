<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticlesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('articles.index'));
        $response->assertSee('Create');
    }

    public function testCreateArticlePage(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->get(route('articles.create'))
            ->assertSee('Create');
    }

/*    public function testShowArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'find this title'
        ]);

        $response = $this->get(route('articles.show', [
            'article' => $article
        ]));
        $response->assertSee('find this title');
    }*/

    public function testCreateNewArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->followingRedirects();

        $response = $this->post(route('articles.store'), [
            'title' => 'Example title',
            'content' => 'Example content'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => 'Example title',
            'content' => 'Example content'
        ]);
    }

    public function testEditArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'find this title'
        ]);

        $this->get(route('articles.edit', [
            'article' => $article
        ]))
            ->assertSee('find this title');
    }

    public function testUpdateArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->followingRedirects();

        $response = $this->put(route('articles.update', $article), [
            'title' => 'New title',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'New title',
        ]);
    }

    public function testDeleteArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('articles', [
            'user_id' => $user->id,
            'title' => $article->title,
            'content' => $article->content
        ]);

        $this->followingRedirects();

        $response = $this->delete(route('articles.destroy', $article));

        $response->assertStatus(200);

        $this->assertSoftDeleted('articles', [
            'user_id' => $user->id,
            'title' => $article->title,
            'content' => $article->content,
        ]);
    }

    public function testRedirectWhenUnauthorizedTriesToCreateArticle(): void
    {
        $response = $this->get(route('articles.create'), [
            'title' => 'Example title',
            'content' => 'Example content',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testRedirectWhenUnauthorizedTriesToSeeCreateForm(): void
    {
        $response = $this->get(route('articles.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testRedirectWhenUnauthorizedTriesToSeeCreateForm1(): void
    {
        $article = Article::factory()->create();

        $response = $this->get(route('articles.create', $article));
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testRedirectWhenUnauthorizedTriesToCreateArticle1(): void
    {
        $article = Article::factory()->create();

        $response = $this->put(route('articles.create', $article), [
            'title' => 'Example title',
            'content' => 'Example content',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testOtherUserCannotUpdateMyArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create();

        $response = $this->put(route('articles.update', $article), [
            'title' => 'New title',
            'content' => 'New Content',
        ]);

        $response->assertStatus(403);
    }

    public function testOtherUserCannotDeleteMyArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create();

        $response = $this->delete(route('articles.update', $article));

        $response->assertStatus(403);
    }

    public function testOtherUserCannotEditMyArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create();

        $response = $this->get(route('articles.edit', [
            'article' => $article
        ]));

        $response->assertDontSee('find this title');
    }

/*    public function testOtherUserCannotStoreMyArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create();
    }*/

    public function testOtherUserCannotCreateMyArticle(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create();

        $response = $this->get(route('articles.create', [
            'article' => $article
        ]));

        $response->assertDontSee('find this title');
    }
}
