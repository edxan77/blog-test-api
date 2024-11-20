<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Auth\Jwt;
use App\Repositories\Blog\IBlogRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    public function __construct(
        private IBlogRepository $blogRepository,
        private Jwt $jwt
    ){}

    public function createBlog(BlogRequest $request)
    {
        $data = $request->validated();
        $image = $request->file('image');
        unset($data['id']);

       if (!$image) {
           throw new HttpResponseException(response()->json([
               'status' => 'INVALID_DATA',
               'errors' => [
                   'image' => 'Image is required'
               ]
           ], 200));
       }

        $data['image'] = base64_encode(file_get_contents($image->path()));
        $data['user_id'] = Auth::guard('api')->id();

        $this->blogRepository->create($data);

        return response()->json([
            'status' => 'OK',
            'message' => 'Blog created successfully'
        ]);
    }

    public function updateBlog(BlogRequest $request)
    {
        $data = $request->validated();
        $blog = $this->blogRepository->getBlogById($data['id']);
        if (isset($data['image'])) {
            $data['image'] = base64_encode(file_get_contents($request->file('image')->path()));
        } else {
            $data['image'] = $blog->image;
        }

        $this->blogRepository->update($data, $blog);

        return response()->json([
            'status' => 'OK',
            'message' => 'Blog updated successfully'
        ]);
    }

    public function deleteBlog(Request $request)
    {
        $blog = $this->blogRepository->getBlogById($request->id);
        $this->blogRepository->delete($blog);

        return response()->json([
            'status' => 'OK',
            'message' => 'Blog deleted successfully'
        ]);
    }

    public function getBlogs()
    {
        return response()->json([
            'status' => 'OK',
            'data' => $this->blogRepository->getAll()
        ]);
    }

    public function getBlog(Request $request, $id)
    {
        $canEdit = false;

        if ($request->token) {
            $canEdit = $this->blogRepository->getBlogById($id)->user_id == $this->jwt->toUser($request->token)->id;
        }

        return response()->json([
            'status' => 'OK',
            'data' => $this->blogRepository->getBlogById($id),
            'can_edit' => $canEdit
        ]);
    }
}
