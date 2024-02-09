<?php

namespace App\Http\Controllers;

use App\Models\SavedPost;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PostSavedController extends Controller
{

    private function calculateReadTime($body)
    {
        $readingSpeed = 200;
        $words = str_word_count(strip_tags($body));
        return ceil($words / $readingSpeed);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $saved = SavedPost::where('user_id', Auth::User()->id)->orderBy('id', 'DESC')->get();

        return view('post.saved', [
            'posts' => $saved,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $post = SavedPost::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'image_path' => (isset($request->image) && $request->image != 'undefined') ? $this->storeImage($request) : null,
            'is_published' => $request->is_published ? 1 : 0,
            'category_id' => $request->category_id ? $request->category_id : null,
            'read_time' => $this->calculateReadTime($request->body),
        ]);

        return response()->json(['message' => 'Zapisano!', 'id' => $post->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return RedirectResponse|Redirector
     */
    public function edit(int $id)
    {
        $saved = SavedPost::findOrFail($id);

        $this->checkUserIdPost($saved);

        return redirect('dashboard/posts/create?edit='.$saved->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $SavedPost = SavedPost::where('id', $id)->firstOrFail();

        $this->checkUserIdPost($SavedPost);

        $input['title'] = $request->title;
        $input['excerpt'] = $request->excerpt;
        $input['body'] = $request->body;
        $input['is_published'] = $request->is_published ? 1 : 0;
        $input['category_id'] = $request->category_id ? $request->category_id : Null;
        $input['read_time'] = $this->calculateReadTime($request->body);

        if (isset($request->image) && $request->image !== 'undefined') {
            $imageExists = str_contains($SavedPost->image_path, $request->image->getClientOriginalName());
            if (!$imageExists) {
                $filePath = base_path('public' . $SavedPost->image_path);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $input['image_path'] = $this->storeImage($request);
            }
        }

        $SavedPost->update($input);

        return response()->json(['message' => 'zapisano']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {
        $SavedPost = SavedPost::findOrFail($id);

        $this->checkUserIdPost($SavedPost);

        $SavedPost->delete();

        return redirect()->back();
    }

    private function storeImage(Request $request)
    {
        $newImageName = uniqid().'-'.$request->image->getClientOriginalName();
        $request->image->move(public_path('images\posts'), $newImageName);

        return '/images/posts/'.$newImageName;
    }

    private function checkUserIdPost(SavedPost $SavedPost): void
    {
        if ($SavedPost->user_id != Auth::id() && ! Auth::User()->hasPermissionTo('post-super-list')) {
            abort(403);
        }
    }
}
