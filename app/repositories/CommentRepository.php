<?php
namespace App\repositories;

use App\Models\Comment;
use Spatie\QueryBuilder\QueryBuilder;

class CommentRepository
{
    public function getAllComments($perPage = 10)
    {
        return QueryBuilder::for(Comment::class) #Spatie query builder
        ->allowedFilters(['user_id', 'post_id'])
            ->allowedSorts(['created_at'])
            ->with(['post', 'user'])
            ->paginate($perPage);
    }

    public function createComment(array $data): Comment
    {
        return Comment::create($data);
    }

    public function getCommentById(int $id): ?Comment
    {
        $comment = Comment::with(['user', 'post'])->find($id);
        if (!$comment) {
            return null;
        }
        return $comment;
    }

    public function updateComment(int $id, array $data): ?Comment
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return null;
        }

        $comment->update($data);

        return $comment;
    }

    public function deleteComment(int $id): void
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return;
        }
        $comment->delete();
    }
}
