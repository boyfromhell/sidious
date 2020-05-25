<?php
namespace App\Http\Controllers;


use App\Events\NewUserOnline;
use App\Events\UserLoggedOut;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $rules = [
            "email" => "email|required",
            "password" => "string|min:6|required"
        ];
        request()->validate($rules);

        $credentials = request(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            event(new NewUserOnline($user->id));
            $token = $user->createToken(env("APP_NAME"))->accessToken;
            return response()->json(compact("user", "token"));
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = Auth::user();
        $user->load(['personnages']);
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $tokens = request()->user()->tokens;
        foreach ($tokens as $token) $token->revoke();
        event(new UserLoggedOut(request()->user()->id));

        return response()->json(['message' => 'Successfully logged out']);
    }
}
