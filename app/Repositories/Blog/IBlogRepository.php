<?php

namespace App\Repositories\Blog;

use App\Models\Blog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

interface IBlogRepository
{
    public function create(array $data): void;
    public function update(array $data, Blog $blog): void;
    public function delete(Blog $blog): void;
    public function getBlogById(int $id): ?Blog;
    public function getAll(): LengthAwarePaginator;
}
