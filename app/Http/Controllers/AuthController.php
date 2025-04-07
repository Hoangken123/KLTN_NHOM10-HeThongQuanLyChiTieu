<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Mail\VerifyEmailMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Đăng ký tài khoản",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"fullName","email","password"},
     *             @OA\Property(property="username", type="string", example="user1"),
     *             @OA\Property(property="email", type="string", format="email", example="a@gmail.com"),
     *             @OA\Property(property="password", type="string", example="123456"),
     *             @OA\Property(property="phone", type="string", example="0912345678"),
     *             @OA\Property(property="address", type="string", example="Hanoi"),
     *             @OA\Property(property="fullName", type="string", example="Nguyen Van A")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đăng ký thành công"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dữ liệu không hợp lệ"
     *     )
     * )
     */

    public function register(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'nullable|unique:users',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'fullName'  => $request->fullName,
            'phone' => $request->phone,
            'monthly_income' => 0,
            'monthly_customer_spending' => 0,
            'currency' => 'VND',
            'isActived' => false,
            'isBlocked' => false,
            'address' => $request->address,
            'avatar' => null,
        ]);

        $token = $user->createToken('thisprivate')->plainTextToken;

        $verifyToken = Str::random(60);
        $user->verify_token = $verifyToken;
        $user->save();

        Mail::to($user->email)->send(new VerifyEmailMail($user, $verifyToken));

        return response()->json([
            'message' => 'Đăng ký thành công, vui lòng kiểm tra email để kích hoạt tài khoản.',
            'user' => $user,
            'token' => $token
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/verify-email",
     *     summary="Xác thực email (kích hoạt tài khoản)",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *         name="token",
     *         in="query",
     *         description="Mã token xác thực được gửi qua email",
     *         required=true,
     *         @OA\Schema(type="string", example="Abc123xyz456token")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tài khoản đã được kích hoạt thành công.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tài khoản đã được kích hoạt thành công.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Token không hợp lệ hoặc đã hết hạn.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Token không hợp lệ hoặc đã hết hạn.")
     *         )
     *     )
     * )
     */

    public function verifyEmail(Request $request)
    {
        $token = $request->query('token');
        $user = User::where('verify_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Token không hợp lệ hoặc đã hết hạn.'], 400);
        }

        $user->isActived = true;
        $user->verify_token = null;
        $user->save();

        return response()->json(['message' => 'Tài khoản đã được kích hoạt thành công.']);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Đăng nhập người dùng",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đăng nhập thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Không có quyền")
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password) || !$user->isActived) {
            return response()->json(['error' => 'Không được phép đăng nhập'], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function resetShow(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email'); 
        return view('comfirm-password.view-reset-password', compact('token', 'email'));
    }
    


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     summary="Gửi link reset mật khẩu",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="a@gmail.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Link reset mật khẩu đã được gửi"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Không thể gửi email"
     *     )
     * )
     */

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);
        $email = $request->email;

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Send email with reset link
        try {
            Mail::to($email)->send(new ResetPasswordMail($token, $email));

            return response()->json([
                'message' => 'Password reset link sent to your email'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Could not send reset link: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     summary="Reset mật khẩu",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "token", "password", "password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", example="a@gmail.com"),
     *             @OA\Property(property="token", type="string", example="abc123token"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reset mật khẩu thành công"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Token không hợp lệ hoặc đã hết hạn"
     *     )
     * )
     */

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return response()->json(['error' => 'Invalid token!'], 400);
        }

        $tokenCreatedAt = Carbon::parse($updatePassword->created_at);
        if (Carbon::now()->diffInMinutes($tokenCreatedAt) > 60) {
            return response()->json(['error' => 'Token has expired!'], 400);
        }

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }
}
