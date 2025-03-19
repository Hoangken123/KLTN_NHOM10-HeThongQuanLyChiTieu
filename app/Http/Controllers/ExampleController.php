<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/example",
     *     summary="Lấy danh sách example",
     *     @OA\Response(
     *         response=200,
     *         description="Thành công"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(['message' => 'Hello Swagger']);
    }
}
