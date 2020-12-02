<?php

namespace App\Events;

use App\Models\Article;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArticleWasCreated
{
    use Dispatchable, SerializesModels;

    public $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function article(): Article
    {
        return $this->article;
    }
}
