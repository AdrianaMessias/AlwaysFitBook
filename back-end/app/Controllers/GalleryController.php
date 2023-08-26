<?php

namespace App\Controllers;

use App\Exceptions\GalleryException;
use App\Facades\Helper;
use App\Facades\Request;
use App\Facades\Response;
use App\Models\Gallery;
use App\Models\User;
use Rakit\Validation\Validator;

class GalleryController extends Controller
{
    public function index()
    {
        if(Helper::checkAuth() != null){
            return Response::json(Gallery::all());
        }else{
            return Response::notAuth();
        }
    }

    public function show(int $id)
    {
        if(Helper::checkAuth() != null){
            $gallery = Gallery::find($id);

            if ($gallery) {
                return $gallery;
            }
            return Response::notFound();
        }else{
            return Response::notAuth();
        }
    }

    public function store()
    {
        $user = Helper::checkAuth();
        if($user != null){
            $data = Request::post();

            $validation = (new Validator())->make($data, [
                "title" => "required",
                "category" => "required",
                "image" => "required",
            ]);

            $uploadpath   = './upload/';
            $parts        = explode(";base64,", $data['image']);
            $imageparts   = explode("image/", @$parts[0]);
            $imagetype    = $imageparts[1];
            $imagebase64  = base64_decode($parts[1]);
            $name = uniqid() . '.png';
            $file         = $uploadpath . $name;
            file_put_contents($file, $imagebase64);

            $validation->validate();

            if ($validation->fails()) {
                return GalleryException::error("O campos título e categoria são obrigatórios", 403);
            }

            $gallery = new Gallery();

            $gallery->title = $data["title"];
            $gallery->description = $data["description"] ?? null;
            $gallery->category = $data["category"];
            $gallery->favorite = false;
            $gallery->user = $user->id;
            $gallery->image = $_ENV["HOST"].'upload/'.$name;
            $gallery->created_at = date('y-m-d h:m:i');
            $gallery->updated_at = date('y-m-d h:m:i');
            
            $gallery->save();

            return Response::json((array)$gallery);
        }else{
            return Response::notAuth();
        }
    }

    public function update(int $id)
    {
        if(Helper::checkAuth() != null){
            $data = Request::update();

            $validation = (new Validator())->make($data, [
                "title" => "required",
                "category" => "required"
            ]);

            $validation->validate();

            $updatedGallery = Gallery::update($id, $data);
            return Response::json($updatedGallery);
        }else{
            return Response::notAuth();
        }
    }

    public function destroy(int $id)
    {
        if(Helper::checkAuth() != null){
            return Gallery::destroy($id);
        }else{
            return Response::notAuth();
        }
    }
}
