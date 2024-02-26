<?php

namespace App\CPU;

use Illuminate\Support\Facades\File;

class Helpers
{
    public static function upload_files($file){
        $domain="https://tqnia.pro-techh.com//";
        $name=time() .str_replace([" ","-"],"_",$file->getClientOriginalName()) ;
        $file->move(public_path("files/"),$name);
        return $domain . "files/$name";

    }
    public static function delete_file($path){

        $file= str_replace("https://tqnia.pro-techh.com//files/","",$path) ;
        
        File::delete(public_path("files/$file"));


        return ;

    }
}
