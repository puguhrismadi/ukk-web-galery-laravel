<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;
class PostController extends Controller
{
    public function index(){
        $type_menu="posts";
        $posts = Post::latest()->paginate(5);
        $data=['type_menu'=>$type_menu,'posts'=>$posts];
        return view('posts.index', $data);
    }
    //create untuk menampilkan form input
    public function create()
    {
        $type_menu="posts";
        $kategori= Kategori::all();
        $data=['type_menu'=>$type_menu,'kategori'=>$kategori];
        return view('posts.create',$data);
    }
    public function show($id){
        $post=Post::where('id',$id);
        $data=['post'=>$post];
        return view('posts.index',$data);
    }

    /**
     * store untuk proses simpan ke tabel post
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //validate form
        $this->validate($request, [
            'gambar'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'judul'     => 'required|min:5',
            'kategori'=> 'required',
            'isi'   => 'required|min:10'
        ]);

        //upload image
        $image = $request->file('gambar');
        $image->storeAs('public/posts', $image->hashName());

        //create post
        Post::create([
            'gambar'     => $image->hashName(),
            'judul'     => $request->judul,
            'kategori_id' => $request->kategori,
            'isi'   => $request->isi,
            'user_id'=> 1,
            'status'=> "Published"
        ]);

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }
    //show form edit
    public function edit(Post $post)
    {
        $type_menu="posts";
        $kategori= Kategori::all();
        $data=['type_menu'=>$type_menu,'kategori'=>$kategori,'post'=>$post];
        return view('posts.edit',$data);
    }

    //function update 
    public function update(Request $request, Post $post)
    {
        //validate form
        $this->validate($request, [
            'gambar'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'judul'     => 'required|min:5',
            'isi'   => 'required|min:10'
        ]);

        //check if image is uploaded
        if ($request->hasFile('gambar')) {
            //upload new image
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/posts', $gambar->hashName());
            //delete old image
            Storage::delete('public/posts/'.$post->gambar);
            //update post with new image
            $post->update([
                'gambar'     => $gambar->hashName(),
                'judul'     => $request->judul,
                'kategori_id' => $request->kategori,
                'isi'   => $request->isi,
                'user_id'=> 1
            ]);

        } else {

            //update post without image
            $post->update([
                'judul'     => $request->judul,
                'isi'   => $request->isi
            ]);
        }

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Diubah!']);
    }
    public function destroy(Post $post)
    {
        //delete image
        Storage::delete('public/posts/'. $post->gambar);

        //delete post
        $post->delete();

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

}
