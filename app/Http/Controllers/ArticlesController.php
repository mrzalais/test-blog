<?php

namespace App\Http\Controllers;

use App\Events\ArticleCountIncreased;
use App\Events\ArticleWasCreated;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show', 'index']]);
    }

    public function index()
    {
        return view('articles.index', [
            'articles' => Article::all()
        ]);
    }

    public function create()
    {
        $this->authorize('create', Article::class);

        return view ('articles.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Article::class);

        $article = (new Article)->fill($request->all());
        $article->user()->associate(auth()->user());
        $article->save();

        event(new ArticleWasCreated($article));

        return redirect()->route('articles.index');
    }

    public function show(Article $article)
    {
        return view('articles.show', [
            'article' => $article
        ]);
    }

    public function edit(Article $article)
    {
        return view('articles.edit', [
            'article' => $article
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $article->update($request->all());

        return redirect()->route('articles.edit', $article);
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        return redirect()->route('articles.index');
    }
}
