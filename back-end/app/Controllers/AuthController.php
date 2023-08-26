<?php

namespace App\Controllers;

use App\Exceptions\UserException;
use App\Facades\JWT;
use App\Facades\Request;
use App\Facades\Response;
use App\Models\User;
use Rakit\Validation\Validator;

class AuthController extends Controller
{
    public function login()
    {
        $data = Request::post();

        $validation = (new Validator())->make($data, [
            "email" => "required|max:255",
            "password" => "required"
        ]);

        $validation->validate();

        if ($validation->fails()) {
            return UserException::error("O e-mail e senha obrigatórios", 403);
        }

        if (!User::check($data["email"], $data["password"])) {
            return Response::json([
                "detail" => "Falha ao realizar login!"
            ], 403);
        }

        $user = User::findByEmail($data["email"]);

        return Response::json([
            "token" => JWT::encode([
                "id"=>$user->id,
                "email" => $user->email
            ])
        ]);
    }

    public function register()
    {
        $data = Request::post();

        $validation = (new Validator())->make($data, [
            "name" => "required|max:255",
            "email" => "required|max:255",
            "password" => "required"
        ]);

        $data['role'] = 'admin';

        $validation->validate();

        if ($validation->fails()) {
            UserException::error("Algo deu errado :(", 403);
        }

        if(User::check_user_email($data['email'])){
            UserException::error("Este e-mail já está sendo utilizado por outro usuário", 400);
        }

        $user = User::create($data);
        return Response::json($user);
    }
}
