<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function store(Request $request)
    {

        $this->validate($request,[
            'pic'=>['required','image','mimes:jpg,png,jpeg'],
            'description'=>['required']
        ]);

        $articles = article::create([
            'pic' => $request->pic->store('articles'),
            'description' => $request->description,
        ]);

        return response()->json([
            'data' => new ArticleResource($articles)
        ])->setStatusCode(201);
    }

    public function update(article $article, Request $request)
    {


        if ($request->hasFile('pic')) {
            $path = $request->pic->storeAs('public/Articles', $request->pic->getClientOriginalName());
            Storage::delete($article->pic);
        } else {
            $path = $article->pic;
        }

        $article->update([
            'description' => $request->get('description', $article->description),
            'pic' => $path,
        ]);


        return response()->json([
            'data' => new ArticleResource($article)
        ])->setStatusCode(200);;
    }


    public function show()
    {

        return response()->json([
            'data' => ArticleResource::collection(article::paginate(10)),


        ])->setStatusCode(200);
    }


    public function destroy(article $article)
    {
        Storage::delete($article->pic);
        $article->delete();
        return response()->json([
            'data' => [
                'message' => 'Cart is delete'
            ]
        ])->setStatusCode(200);
    }


    public function index(article $article)
    {
        return response()->json([
            'data' => new ArticleResource($article),

        ])->setStatusCode(200);
    }
}
