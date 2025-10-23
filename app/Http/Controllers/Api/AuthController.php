<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ]);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|max:255|email',
            'password' => 'required|min:8',
        ]);

        $user = User::Where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credential'
            ], 401);
        }

        $token = $user->createToken('app-tasks')->plainTextToken;

        return response()->json([
            'message' => 'User login successfully',
            'token' => $token,
            'user' => json_encode($user)
        ], 200);
    }

    public function forgotpassword(Request $request)
    {
        $request->validate([
            'email' => 'required|max:255|email',
        ]);

        try {

            $email = $request->email;
            $token = Str::random(64);
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            DB::table('password_reset_tokens')->insert([
                'email' => $email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::send('mail.forgotpassword', [
                'token' => $token,
                'email' => $email
            ], function ($message) use ($email) {
                $message->to($email);
                $message->subject("reset password");
            });
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 422);
        }


        return response()->json([
            'message' => 'We have emailed reset password',
        ], 200);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $email = $request->email;
            $checkEmailToken = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->where('token', $request->token)
                ->first();

            if (!$checkEmailToken) {
                return response()->json([
                    'message' => "Invalid token or meil",
                ], 422);
            }

            User::where('email', $email)->update(['password' => Hash::make($request->password)]);
            DB::table('password_reset_tokens')->where('email', $email)->delete();

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Your password changed',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
