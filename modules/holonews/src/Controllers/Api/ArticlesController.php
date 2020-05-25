<?php
namespace Modules\Holonews\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Holonews\Models\Article;

class ArticlesController extends Controller
{
    /**
     * Get all posts with tags and author
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function all()
    {
        $all = Article::with(['author', 'tags'])
            ->when(request()->has('search'), function ($q) {
                $q->where('title', 'LIKE', '%' . request('search') . '%');
            })
            ->when(request('author_id'), function ($q, $value) {
                $q->whereAuthorId($value);
            })
            ->when(request('tag_id'), function ($q, $value) {
                $q->whereHas('tags', function ($query) use ($value) {
                    $query->where('id', $value);
                });
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(4);
        return $all;
    }

    public function highlights()
    {
        $highlights = Article::with(['author', 'tags'])
            ->orderBy('created_at', 'DESC')
            ->take(4)->get();
    }

    /**
     * Get one post with slug
     *
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|Article
     */
    public function get(string $slug)
    {
        return Article::with(['author', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
