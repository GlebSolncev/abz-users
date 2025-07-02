<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PositionCollection;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $positions = Cache::remember('position-list', 3600, fN() =>
            Position::query()->select(['id','name'])->get()
        );

        if ($positions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Positions not found',
            ], Response::HTTP_NOT_FOUND); // 404
        }

        return PositionCollection::make($positions)->toArray($request);
    }
}
