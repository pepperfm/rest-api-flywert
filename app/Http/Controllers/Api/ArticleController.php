<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Exception;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\User;

class ArticleController extends Controller
{
    use SoftDeletes;

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $article = new Article;
        $article->text = $request->text;
        $userIds = array_merge([Auth::user()->id], $request->user_ids);

        if ($article->save()) {
            $article->users()->attach(User::find($userIds));

            return new JsonResponse(['message' => 'Статья добавлена']);
        }

        return new JsonResponse(['message' => 'Ошибка добавления'], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $article = Article::find($id);

        if (!$article) {
            return new JsonResponse(['message' => 'Статья не найдена'], 404);
        }

        return new JsonResponse([
            'id' => $id,
            'text' => $article->text
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $article = Article::find($id);

        // если список статей выводился бы куда-то на фронт в админку, то я вижу, как это сделать через политики.
        // а тут как иначе сделать, кроме такой проверки, т.к. это апи, я не додумался
        if (!Auth::user()->isAuthor($article)) {
            return new JsonResponse(['message' => 'Не ваша статья'], 403);
        }

        $article->text = $request->text;

        if ($article->save()) {
            return new JsonResponse(['message' => 'Статья обновлена']);
        }

        return new JsonResponse(['message' => 'Ошибка обновления'], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $article = Article::find($id);

        if (Auth::user()->isAuthor($article)) {
            return new JsonResponse(['message' => 'Не ваша статья'], 403);
        }

        try {
            $article->users()->detach();
            $article->delete();

            return new JsonResponse(['message' => 'Статья удалена']);
        } catch (Exception $e) {
            Log::debug($e->getMessage());

            return new JsonResponse(['message' => 'Ошибка удаления'], 422);
        }

    }
}
