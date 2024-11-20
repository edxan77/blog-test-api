<?php

namespace App\Repositories\Blog;

use App\Models\Blog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class BlogRepository implements IBlogRepository
{

    public function create(array $data): void
    {
        Blog::create($data);
    }

    public function update(array $data, Blog $blog): void
    {
       $blog->update($data);
    }

    public function delete(Blog $blog): void
    {
        $blog->delete();
    }

    public function getBlogById(int $id): ?Blog
    {
        return Blog::with('user')->where('id', $id)->first();
    }

    public function getAll(): LengthAwarePaginator
    {
        return Blog::with('user')->paginate(1);
    }
}
