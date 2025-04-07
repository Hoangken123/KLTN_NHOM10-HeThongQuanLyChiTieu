<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all users",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="user1"),
     *                 @OA\Property(property="email", type="string", format="email", example="user1@example.com"),
     *                 @OA\Property(property="phone", type="string", example="123456789"),
     *                 @OA\Property(property="fullName", type="string", example="John Doe"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="avatar", type="string", example="avatars/user1.jpg")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * @OA\Put(
     *     path="/api/user/update-profile",
     *     summary="Cập nhật hồ sơ người dùng",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "email", "phone", "fullName", "address"},
     *             @OA\Property(property="username", type="string", example="user1"),
     *             @OA\Property(property="email", type="string", format="email", example="user1@example.com"),
     *             @OA\Property(property="phone", type="string", example="123456789"),
     *             @OA\Property(property="fullName", type="string", example="John Doe"),
     *             @OA\Property(property="address", type="string", example="123 Main St")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật hồ sơ thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật hồ sơ thành công"),
     *             @OA\Property(property="user", type="object", 
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="user1"),
     *                 @OA\Property(property="email", type="string", format="email", example="user1@example.com"),
     *                 @OA\Property(property="phone", type="string", example="123456789"),
     *                 @OA\Property(property="fullName", type="string", example="John Doe"),
     *                 @OA\Property(property="address", type="string", example="123 Main St"),
     *                 @OA\Property(property="avatar", type="string", example="avatars/user1.jpg")
     *             )
     *         )
     *     )
     * )
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'fullName' => 'string|max:255',
            'address' => 'nullable|string|max:255',
        ]);

        User::where('id', $user->id)->update($request->only(['username', 'email', 'phone', 'fullName', 'address']));

        $user = User::find($user->id);

        return response()->json(['message' => 'Cập nhật hồ sơ thành công', 'user' => $user]);
    }

    /**
     * @OA\Put(
     *     path="/api/user/avatar",
     *     summary="Cập nhật ảnh đại diện người dùng",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"avatar"},
     *             @OA\Property(property="avatar", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cập nhật ảnh đại diện thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Cập nhật ảnh đại diện thành công"),
     *             @OA\Property(property="avatar", type="string", example="avatars/123456.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu không hợp lệ"
     *     )
     * )
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        if ($user->avatar) {
            Storage::delete($user->avatar);
        }

        // Lưu ảnh mới
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        User::where('id', $user->id)->update(['avatar' => $path]);

        return response()->json(['message' => 'Cập nhật ảnh đại diện thành công', 'avatar' => $path]);
    }

    /**
     * @OA\Delete(
     *     path="/api/user/{id}",
     *     summary="Xóa người dùng",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của người dùng",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Xóa người dùng thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Xóa người dùng thành công")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Người dùng không tồn tại"
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Xóa người dùng thành công']);
    }


    /**
     * @OA\Get(
     *     path="/api/user/{id}",
     *     summary="Get user by ID",
     *     summary="Lấy thông tin người dùng theo ID",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của người dùng",
     *         required=true,
     *         required=true,
     *         description="ID của người dùng",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin người dùng",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="username", type="string", example="user1"),
     *             @OA\Property(property="email", type="string", format="email", example="user1@example.com"),
     *             @OA\Property(property="email", type="string", example="user1@example.com"),
     *             @OA\Property(property="phone", type="string", example="123456789"),
     *             @OA\Property(property="fullName", type="string", example="John Doe"),
     *             @OA\Property(property="address", type="string", example="123 Main St"),
     *             @OA\Property(property="avatar", type="string", example="avatars/user1.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không tìm thấy người dùng"
     *     )
     * )
     */
    public function edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng'], 404);
        }

        return response()->json($user);
    }
}
