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
        return Post::with(['user', 'comments'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Post::create($data);
    }

    public function updatePost(int $id, array $data): Post
    {
        $post = Post::findOrFail($id);
        $post->update($data);
        return $post;
    }

    public function deletePost(int $id): void
    {
        $post = Post::findOrFail($id)->delete();
    }

}
