<?php

namespace App\Http\Controllers\Api;

use App\CPU\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\PinnedPosts;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)  {
        $posts= PostResource::collection( 
            Post::where("user_id",$request->user()->id)
            ->orderBy("pinned","DESC")
            ->orderBy("id","DESC")
            ->with(["tags"])->get()
            );
        return $this->Response($posts,"Your Posts",200);
    }


    public function store(Request $request)  {
        $validator=Validator::make($request->all(),[
            "title"=>"required",
            "body"=>"required",
            "image"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);
        $post=Post::create([
            "title"=>$request->title,
            "body"=>$request->body,
            "image"=>Helpers::upload_files($request->image),
            "user_id"=>$request->user()->id,
        ]);
        if($request->has('tags') )
            $post->tags()->attach($request->tags);

        return $this->Response( new PostResource( $post ),"Post Created Successfully",200);
    }


    public function show(Request $request,$post_id=null)  {


        $post=Post::where("id",$post_id)->where("user_id",$request->user()->id)->with(['tags'])->first();
        if($post==null)
            return $this->Response(null,"Not Allowed",400);

        return $this->Response( new PostResource( $post ),"show post",200);
    }


    public function update(Request $request)  {
        $validator=Validator::make($request->all(),[

            "post_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);

        $post=Post::where("id",$request->post_id)->where("user_id",$request->user()->id)->with(['tags'])->first();
        if($post==null)
            return $this->Response(null,"Not Allowed",400);




        $post->update([
            "title"=>$request->title ?? $post->title,
            "body"=>$request->body ?? $post->body,
        ]);
        if($request->hasFile('image')){
            Helpers::delete_file($request->image);
            $post->update([
                "image"=>Helpers::upload_files($request->image),
            ]);
        }
        if($request->has('tags') )
            $post->tags()->sync($request->tags);

        $post=Post::where("id",$request->post_id)->with(["tags"])->first();
        return $this->Response( new PostResource( $post ),"Post Updated Successfully",200);
    }
    
    public function delete(Request $request)  {
        $validator=Validator::make($request->all(),[
            "post_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);

        $post=Post::where("id",$request->post_id)->where("user_id",$request->user()->id)->with(['tags'])->first();
        if($post==null)
            return $this->Response(null,"Not Allowed",400);

        $post->delete();


        return $this->Response( new PostResource( $post ),"Post Deleted  Successfully",200);
    }


    public function deleted_posts(Request $request)  {
        $post=Post::where("user_id",$request->user()->id)
        ->onlyTrashed()
        ->with(['tags'])->first();
        if($post==null)
            return $this->Response(null,"There is No Posts in Trash",400);

        return $this->Response( new PostResource( $post )," Deleted  Posts",200);
    }

    public function restore_post(Request $request)  {
        $validator=Validator::make($request->all(),[
            "post_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);


        $post=Post::where("id",$request->post_id)->where("user_id",$request->user()->id)->withTrashed()->first();
        if($post==null)
            return $this->Response(null,"Not Allowed",400);


        $post->restore();


        return $this->Response( new PostResource( $post )," restored  Successfully",200);
    }

    public function pinned_post(Request $request)  {
        $validator=Validator::make($request->all(),[
            "post_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);


        $post=Post::where("id",$request->post_id)->where("user_id",$request->user()->id)->first();
        if($post==null)
            return $this->Response(null,"Not Allowed",400);

        Post::where("user_id",$request->user()->id)->where("pinned",true)->update([
            "pinned"=>false,
        ]);
        $post->update([
            "pinned"=>true,
        ]);
        

        return $this->Response( new PostResource( $post ),"Post Pinned  Successfully",200);
    }
    public function remove_pinned_post(Request $request){
        $validator=Validator::make($request->all(),[
            "post_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);


        $post=Post::where("id",$request->post_id)->where("user_id",$request->user()->id)->first();
        if($post==null)
            return $this->Response(null,"Not Allowed",400);
        $post->update([
            "pinned"=>false,
        ]);

        return $this->Response( new PostResource( $post )," Pinned removed Successfully",200);


    }
    
}
