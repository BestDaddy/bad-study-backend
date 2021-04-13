<?php


namespace App\Http\Controllers\Api\Auth;


use App\Exceptions\ApiServiceException;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Auth\LoginApiRequest;
use App\Http\Requests\Api\Auth\RegisterApiRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiBaseController
{
    public function authenticate(LoginApiRequest $request)
    {
        $user = User::with('role')
            ->where('email', $request->email)
            ->first();

        if (empty($user)) {
            throw new ApiServiceException(400, false,
                [
                    'message' => 'Пользователь не зарегистрирован',
                ]);
        }
        $credentials = request(['email', 'password']);

        if (!($token = $this->guard()->attempt($credentials))) {
            throw new ApiServiceException(400, false,
                [
                    'message' => 'Неверный пароль или почта',
                ]);
        }

        return [
            'token' => $token,
            'user' => new UserResource($user),
            'expires_in' => $this->guard()->factory()->getTTL() * 60 * 12
        ];

    }

    public function me(){
        $user = Auth::user();
        $user->load('role');
        return $this->successResponse(
            UserResource::make($user)
        );
    }

    public function logout()
    {
        $this->guard()->logout();

        return $this->successResponse(
            ['message' => 'Successfully logged out']
        );
    }

    public function register(RegisterApiRequest $request){
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' =>bcrypt($request->password),
            'role_id' => Role::STUDENT_ID,
        ]);

        return $this->successResponse([
            [
                'message' => 'Successfully registered',
                'user' => UserResource::make($user)
            ]
        ]);
    }

    public function guard()
    {
        return auth()->guard('api');
    }
}
