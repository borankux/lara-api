<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
    }

    public function vcode(Request $request)
    {
        $phone = $this->parseRequest($request, [
            'body_name' => 'body_mobile'
        ]);
        $phone = $request->get('phone');
        $data = [
            'phone' => $phone
];
        return $this->ok($data); 
    }
}
