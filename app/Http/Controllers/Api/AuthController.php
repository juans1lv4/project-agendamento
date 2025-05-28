<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email'=> 'requerid|string|email',
            'success'=>'required|string'
        ]);
        return response()->json([
            'mensagem'=> $request->all()
        ]);
    }
}
