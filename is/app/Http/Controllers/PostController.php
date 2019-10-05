<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //create a variable and store all the blog posts in it from the database
        $posts = Post::orderBy('id','desc')->paginate(10);
        //return a view and pass in the above variable
        return view('posts.index')->withPosts($posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         //validate the data
        $this->validate($request, array(
          'title'=>'required|max:255',
          'slug'=>'required|alpha_dash|min:5|max:255|unique:posts,slug',
          'body'=>'required'
        ));
        //store in the database
        $post = new Post;
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->body = $request->body;  
        //save our image
        if($request->hasFile('featured_image'))
        {
            $image = $request->file('featured_image');
            $filename = time() .'.'.$image->getClientOriginalExtension();
            $location = public_path('images/'.$filename);
            Image::make($image)->resize(800,400)->save($location);
            $post->image = $filename;
        }   
        $post->save();
        //flash sessions are used when you want the output to come after only one page request
        //Session::flash('key','value')
        Session::flash('success','The blog post was successfully saved!');
        //redirect to another page
        return redirect()->route('posts.show',$post->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post',$post);
        //you can alternatively do ...=>withPost($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //find the post in the database andsave it as a variable
        $post = Post::find($id);
        //return a view and pass the variable we previously created
        return view('posts.edit')->withPost($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                //validate the data before we use it
        $post = Post::find($id);
        if ($request->input('slug') == $post->slug) 
        {
        $this->validate($request, array(
          'title'=>'required|max:255',
          'body'=>'required'
        ));
        }
        else
           {
             $this->validate($request, array(
          'title'=>'required|max:255',
          'slug'=>'reuired|alpha_dash|min:5|max:255|unique:posts,slug',
          'body'=>'required'
        ));
           } 
        //save the data to the database
         $post = Post::find($id);
         $post->title = $request->input('title');
         $post->slug = $request->input('slug');
         $post->body = $request->input('body');
         $post->save();
        //set flash data with success message
        Session::flash('success','This post was successfully saved.');
        //redirect with flash data in posts.show
        return redirect()->route('posts.show',$post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find post in the database and save it in a variable
        $post = Post::find($id);
        //delete method
        $post->delete();
        //set flash data with success message
        Session::flash('success','This post was successfully deleted.');
        //redirect to the index page
        return redirect()->route('posts.index');

    }
}
