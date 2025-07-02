<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UserConflictException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        return UserCollection::make(
            User::with(['position'])->orderByDesc('created_at')->paginate(6)
        )->toArray($request);
    }

    /**
     * @param User $user
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return UserResource::make($user->load('position'));
    }

    /**
     * @param UserStoreRequest $request
     * @param UserService $services
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request, UserService $services): JsonResponse
    {
        try {
            $user = $services->createUser($request->validated());
            $user->load('position');
        } catch (UserConflictException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_CONFLICT);
        }

        return response()->json([
            'success' => true,
            'user'    => UserResource::make($user),
        ], Response::HTTP_CREATED);
    }
}
