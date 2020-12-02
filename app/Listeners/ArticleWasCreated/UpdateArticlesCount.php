<?php

namespace App\Listeners\ArticleWasCreated;

use App\Events\ArticleWasCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateArticlesCount
{
    public function handle(ArticleWasCreated $event)
    {
        $user = $event->article()->user;

        $user->update([
            'articles_count' => $user->articles()->count()
        ]);
    }
}
