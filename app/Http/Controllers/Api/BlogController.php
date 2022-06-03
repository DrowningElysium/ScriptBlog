<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\BlogCollection;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\BlogTag;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api'], [
            'except' => ['index', 'show']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Blog::query()->with(['writer', 'tags']);
        if ($tag = $request->query('tag')) {
            $query->tag($tag);
        }

        // Normal users/guests can't decide state
        if (!$request->user() || !$request->user()->is_admin) {
            return response()->json(new BlogCollection($query->published()->get()));
        }

        // Allow state to be queried
        $state = $request->query('state');
        if (in_array($state, ['published', 'unpublished'])) {
            $query->{$state}();
        }

        return response()->json(new BlogCollection($query->get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreBlogRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBlogRequest $request): JsonResponse
    {
        $blog = Blog::create($request->validated());
        foreach ($request->input('tags') as $tag) {
            $blog->tags()->attach(BlogTag::firstOrCreate(['title' => $tag]));
        }

        $blog->load(['writer', 'tags']);

        return response()->json(new BlogResource($blog));
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Blog $blog
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, Blog $blog): JsonResponse
    {
        $this->authorize('view', $blog);

        $blog->load(['writer', 'tags']);

        return response()->json(new BlogResource($blog));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateBlogRequest $request
     * @param \App\Models\Blog $blog
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateBlogRequest $request, Blog $blog): JsonResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Blog $blog
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Blog $blog): JsonResponse
    {
        //
    }
}
