<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    use ApiResponse;
    public function __construct(protected AuthService $authService)
    {
    }
    public function login(LoginRequest $request)
    {
        try {
        $data = $this->authService->login(
            $request->validated()
        );

        return $this->success([
            'token'      => $data['token'],
            'expires_at' => $data['expires_at'],
            'user'       => new UserResource($data['user']),
        ], 'Login successful');

    } catch (\Exception $e) {
        return $this->error($e->getMessage(), 401);
    }
    }
    public function logout(request $request){
        $this->authService->logout($request->user());
        return $this->success([],'Logged out successfully');
    }
    public function me(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return $this->error('Unauthenticated', 401);
    }

    return $this->success(
        ['user' => new UserResource($user)],
        'Authenticated user'
    );
}


}