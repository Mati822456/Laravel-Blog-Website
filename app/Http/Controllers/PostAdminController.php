<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\SavedPost;
use App\Models\HistoryPost;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostUpdateFormRequest;

class PostAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:post-list', ['only' => ['index']]);
        $this->middleware('permission:post-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:post-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:post-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (isset($request->order)) {
            $order = $request->order;
        } else {
            $order = 'desc';
        }
        if (isset($request->limit)) {
            $limit = $request->limit;
        } else {
            $limit = 20;
        }
        if (isset($request->user) && $request->user != 0) {
            $user = $request->user;
        } else {
            $user = 0;
        }

        if (Auth::User()->hasRole('Admin')) {
            if ($user != 0) {
                $posts = Post::where('user_id', $request->user)->orderBy('id', $order);
            } else {
                $posts = Post::orderBy('id', $order);
            }
        } else {
            $posts = Post::orderBy('id', $order)->where('user_id', Auth::User()->id);
        }

        if ($request->categories && $request->categories[0] !== null) {
            $temp = explode(',', $request->categories[0]);
            $posts->whereIn('category_id', $temp);
            $selected_categories = Category::whereIn('id', $temp)->get();
            $selected_categories_array = $temp;
        } else {
            $selected_categories = null;
            $selected_categories_array = null;
        }

        $users = User::all();

        $categories = Category::all();

        return view('post.index', [
            'posts' => $posts->paginate($limit),
            'users' => $users,
            'order' => $order,
            'limit' => $limit,
            'selectedUser' => $user,
            'categories' => $categories,
            'selected_categories' => $selected_categories,
            'selected_categories_array' => $selected_categories_array,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $saved = SavedPost::where('user_id', Auth::User()->id)->get();
        $categories = Category::all();

        if (count($saved) > 0 && ! $request->new && ! $request->edit) {
            return redirect()->route('posts.saved');
        }

        if ($request->edit) {
            $saved = SavedPost::findOrFail($request->edit);

            if ($saved->user_id != Auth::User()->id) {
                abort(404);
            }

            return view('post.create', [
                'post' => $saved,
                'categories' => $categories,
            ]);
        }

        return view('post.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $SavedPost = SavedPost::find($request->id_saved_post);

        $this->checkUserIdPost(null, $SavedPost);

        $validation = [
            'title' => 'required|max:255|unique:posts,title',
            'excerpt' => 'required|max:510',
            'body' => 'required',
            'category_id' => 'required',
        ];

        if (! isset($request->image)) {
            if (isset($SavedPost->image_path)) {
                $request['image_path'] = $SavedPost->image_path;
                $request->except('image');

                $validation += ['image_path' => 'required'];
            } else {
                $validation += ['image' => 'required|mimes:png,jpg,jpeg|max:10248'];
            }
        } else {
            $validation += ['image' => 'required|mimes:png,jpg,jpeg|max:10248'];
        }

        $request->validate(
            $validation
        );

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'image_path' => isset($request->image_path) ? $request->image_path : $this->storeImage($request),
            'slug' => Str::slug($request->title),
            'is_published' => $request->is_published == 'on' ? true : false,
            'category_id' => $request->category_id,
        ]);

        if ($SavedPost) {
            $SavedPost->delete();
        }

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::with('category')->findOrFail($id);

        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        $categories = Category::all();

        $this->checkUserIdPost($post);

        return view('post.edit', [
            'post' => $post,
            'categories' => $categories,
            'editPost' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostUpdateFormRequest $request, $id)
    {
        $request->validated();

        $post = Post::where('id', $id);

        if ($post->get()->isEmpty()) {
            abort(404);
        }

        $this->checkUserIdPost($post->get()[0]);

        HistoryPost::create([
            'post_id' => $post->get()[0]->id,
            'title' => $post->get()[0]->title,
            'excerpt' => $post->get()[0]->excerpt,
            'body' => $post->get()[0]->body,
            'image_path' => $post->get()[0]->image_path,
            'slug' => $post->get()[0]->slug,
            'is_published' => $post->get()[0]->is_published,
            'additional_info' => $post->get()[0]->additional_info,
            'category_id' => $post->get()[0]->category_id,
        ]);

        $input['title'] = $request->title;
        $input['excerpt'] = $request->excerpt;
        $input['body'] = $request->body;
        $input['slug'] = Str::slug($request->title);
        $input['is_published'] = $request->is_published == 'on' ? true : false;
        $input['additional_info'] = 0;
        $input['category_id'] = $request->category_id;

        if ($request->image) {
            $input['image_path'] = $this->storeImage($request);
        }

        $post->update($input);

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        $this->checkUserIdPost($post);

        $post->delete();

        return redirect()->route('posts.index');
    }

    private function storeImage($request)
    {
        $newImageName = uniqid().'-'.$request->image->getClientOriginalName();
        $request->image->move(public_path('images'), $newImageName);

        return '/images/'.$newImageName;
    }

    private function checkUserIdPost(Post $post = null, SavedPost $savedPost = null): void
    {
        if ($post) {
            if ($post->user_id != Auth::id() && ! Auth::User()->hasRole('Admin')) {
                abort(403);
            }
        }
        if ($savedPost) {
            if ($savedPost->user_id != Auth::id() && ! Auth::User()->hasRole('Admin')) {
                abort(403);
            }
        }
    }
}
