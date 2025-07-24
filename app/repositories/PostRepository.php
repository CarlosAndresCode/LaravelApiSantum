<?php

namespace App\repositories;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class PostRepository
{
    public function getAllPosts($perPage = 10): LengthAwarePaginator
    {
        return QueryBuilder::for(Post::class)
            ->allowedFilters(['title', 'content', 'user_id'])
            ->allowedSorts(['title', 'created_at', 'updated_at'])
            ->with(['user', 'comments'])
            ->paginate($perPage);
    }

    public function getPostById(int $id): ?Post
    {
        $post = Post::with(['user', 'comments'])->find($id);

        if (!$post) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Post with ID {$id} not found.");
        }

        return $post;
    }

    public function createPost(array $data)
    {
        return Post::create($data);
    }

    public function updatePost(int $id, array $data): Post
    {
        $post = Post::with(['user', 'comments'])->find($id);

        if (!$post) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Post with ID {$id} not found.");
        }

        $post->update($data);
        return $post;
    }

    public function deletePost(int $id): void
    {
        $post = Post::with(['user', 'comments'])->find($id);

        if (!$post) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Post with ID {$id} not found.");
        }

        $post->delete();
    }

}
