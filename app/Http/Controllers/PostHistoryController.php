<?php

namespace App\Http\Controllers;

use App\Models\HistoryPost;
use App\Models\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PostHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:post-list', ['only' => ['index']]);
        $this->middleware('permission:post-edit', ['only' => ['revert', 'update']]);
        $this->middleware('permission:post-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int $id
     * @return Factory|View
     */
    public function index(int $id)
    {
        $historyPosts = HistoryPost::where('post_id', $id)->orderBy('id', 'DESC')->get();
        $currentPost = Post::findOrFail($id);

        $this->checkUserIdPost($currentPost);

        return view('history.index', [
            'posts' => $historyPosts,
            'currentPost' => $currentPost,
            'id' => $id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param  mixed $history_id
     * @return Factory|View
     */
    public function show(int $id, mixed $history_id)
    {
        $currentPost = Post::findOrFail($id);

        $this->checkUserIdPost($currentPost);

        if ($history_id === 'current') {
            $post = $currentPost;
        } else {
            $post = HistoryPost::findOrFail($history_id);
        }

        $historyPosts = HistoryPost::where('post_id', $id)->orderBy('id', 'desc')->get();

        return view('history.show', [
            'post' => $post,
            'currentPost' => $currentPost,
            'historyPosts' => $historyPosts,
            'id' => $id,
            'history_id' => $history_id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param  mixed $history_id
     * @return JsonResponse
     */
    public function showJson(int $id, mixed $history_id)
    {
        $currentPost = Post::with('category', 'user', 'changeUser')->findOrFail($id);

        $this->checkUserIdPost($currentPost);

        if ($history_id === 'current') {
            $post = $currentPost;
        } else {
            $post = HistoryPost::with('category', 'changeUser')->findOrFail($history_id);
        }

        return response()->json($post);
    }

    /**
     * Revert the specified resource.
     *
     * @param  int $postid
     * @param  int $historyid
     * @return RedirectResponse
     */
    public function revert(int $postid, int $historyid)
    {
        $post = Post::findOrFail($postid);

        $this->checkUserIdPost($post);

        $historyPost = HistoryPost::findOrFail($historyid);

        HistoryPost::create([
            'post_id' => $post->id,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'body' => $post->body,
            'image_path' => $post->image_path,
            'slug' => $post->slug,
            'is_published' => $post->is_published,
            'additional_info' => $post->additional_info,
            'category_id' => $post->category_id,
            'read_time' => $post->read_time,
            'change_user_id' => $post->change_user_id,
            'changelog' => $post->changelog,
        ]);

        $post->update([
            'title' => $historyPost->title,
            'excerpt' => $historyPost->excerpt,
            'body' => $historyPost->body,
            'is_published' => $historyPost->is_published,
            'image_path' => $historyPost->image_path,
            'slug' => $historyPost->slug,
            'additional_info' => 1,
            'category_id' => $historyPost->category_id,
            'read_time' => $historyPost->read_time,
            'change_user_id' => $historyPost->change_user_id,
            'changelog' => null,
        ]);

        return redirect()->route('posts.edit', ['post' => $postid]);
    }

    private function checkUserIdPost(Post $post = null): void
    {
        if ($post) {
            if ($post->user_id != Auth::id() && !Auth::User()->hasRole('Admin')) {
                abort(403);
            }
        }
    }
}
