<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    /**
     * Return the 15 most recently created articles.
     */
    public function index(): JsonResponse
    {
        $articles = Article::orderBy('created_at', 'desc')->limit(15)->get();
        return response()->json($articles);
    }

    /**
     * Show a specific article and increment the view count.
     */
    public function show(int $id): JsonResponse
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $article->increment('views');
        return response()->json($article);
    }

    /**
     * Store a new article with validation.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:64',
            'url' => 'required|url|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $article = Article::create([
            'title' => $request->input('title'),
            'url' => $request->input('url'),
        ]);

        return response()->json($article, 201);
    }

    /**
     * Update an existing article.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:64',
            'url' => 'required|url|max:1024',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $article->update([
            'title' => $request->input('title'),
            'url' => $request->input('url'),
        ]);

        return response()->json($article);
    }

    /**
     * Delete an article.
     */
    public function destroy(int $id): JsonResponse
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $article->delete();

        return response()->json(['message' => 'Article deleted']);
    }
}
