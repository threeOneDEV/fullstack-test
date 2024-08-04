<?php

namespace App\Controllers;

use App\Models\Comment;
use CodeIgniter\HTTP\Request;

class CommentController extends BaseController
{
    public function index()
    {
        return view('comment/index');
    }

    public function getComments()
    {
        $commentModel = new Comment;

        $page = $this->request->getGet('page') ?? 1;
        $perPage = 3;
        $offset = ($page - 1) * $perPage;

        $comments = $commentModel->orderBy('id', 'ASC')->findAll($perPage, $offset);
        $totalComments = $commentModel->countAll();
        $totalPages = ceil($totalComments / $perPage);

        $commentsForJS = array_map(function ($comment) {
            return [
                'id' => $comment['id'],
                'date' => $comment['date'],
                'name' => $comment['name'],
                'text' => $comment['text']
            ];
        }, $comments);

        return $this->response->setJSON([
            'comments' => $commentsForJS,
            'totalPages' => $totalPages
        ]);
    }


    public function store()
    {
        $commentModel = new Comment;
        $data = [
            'name' => $this->request->getPost('name'),
            'date' => $this->request->getPost('date'),
            'text' => $this->request->getPost('text'),
        ];

        $commentModel->save($data);
        $newCommentId = $commentModel->getInsertID();
        $newComment = $commentModel->find($newCommentId);
        $totalComments = $commentModel->countAll();

        return $this->response->setJSON(['success' => true, 'comment' => $newComment, 'totalComments' => $totalComments]);
    }

    public function delete($id)
    {
        $commentModel = new Comment;

        if ($commentModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error'], 400);
        }
    }
}
