<?php

namespace App\Http\Controllers\Api;

use App\Models\Tags;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Validator;

class TagsController extends Controller
{
    use ResponseTrait;
    public function index(Request $request){
        $tags= TagResource::collection( Tags::orderBy("id","DESC")->get());
        
        if(count($tags) == 0)
            return $this->Response(null,"There is no tags",200);

        return $this->Response($tags,"All Tags ",200);
    }

    public function add(Request $request){

        $validator=Validator::make($request->all(),[
            "name"=>"required|unique:tags,name",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);
        $tag=Tags::create([
            "name"=>$request->name,
        ]);
        return $this->Response( new TagResource( $tag),"Added Successfully",201);
    }
    public function update(Request $request){
        $validator=Validator::make($request->all(),[
            "name"=>"required|unique:tags,name",
            "tag_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);

        $tag=Tags::find($request->tag_id);
        if($tag==null)
            return $this->Response(null,"Tag Not Found",400);

        $tag->update([
            "name"=>$request->name,
        ]);

        return $this->Response(new TagResource( $tag),"Updated Successfully",201);
    }
    public function delete(Request $request){
        $validator=Validator::make($request->all(),[
            "tag_id"=>"required",
        ]);
        if($validator->fails())
            return $this->Response($validator->errors(),"Data Not Valid",400);

        $tag=Tags::find($request->tag_id);
        if($tag==null)
            return $this->Response(null,"Tag Not Found",400);

        $tag->delete();

        return $this->Response(new TagResource( $tag),"Deleted Successfully",201);
    }
}
