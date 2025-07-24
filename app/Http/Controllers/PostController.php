<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginatedRequest;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\repositories\PostRepository;

class PostController extends Controller
{
    public function __construct(protected PostRepository $postRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaginatedRequest $request)
    {
        $posts = $this->postRepository->getAllPosts($request->input('per_page', 10));

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateRequest $request)
    {
        $post = $this->postRepository->createPost($request->validated());

        return new PostResource($post);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $post)
    {
        $post = $this->postRepository->getPostById($post);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, int $post)
    {
        $post = $this->postRepository->updatePost($post, $request->validated());

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $post)
    {
        $this->postRepository->deletePost($post);

        return response()->json([
            'message' => 'Post deleted successfully',
        ], 204);
    }
}
