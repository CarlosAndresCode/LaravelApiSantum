<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Requests\PaginatedRequest;
use App\Http\Resources\CommentResource;
use App\repositories\CommentRepository;

class CommentController extends Controller
{
    public function __construct(protected CommentRepository $commentRepository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PaginatedRequest $request)
    {
        $comments = $this->commentRepository->getAllComments($request->input('per_page', 10));

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentCreateRequest $request)
    {
        $comment = $this->commentRepository->createComment($request->validated());

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $comment)
    {
        $comment = $this->commentRepository->getCommentById($comment);

        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, int $comment)
    {
        $comment = $this->commentRepository->updateComment($comment, $request->validated());

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $comment)
    {
        $this->commentRepository->deleteComment($comment);

        return response()->json([
                'message' => 'Comment deleted successfully',
            ], 204
        );
    }
}
