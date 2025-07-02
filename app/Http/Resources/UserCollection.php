<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public $collects = UserResource::class;

    public static $wrap = null;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentPage = $this->currentPage();
        $perPage     = $this->perPage();
        $lastPage    = $this->lastPage();
        $total       = $this->total();

        return [
            'success'     => true,
            'page'        => $currentPage,
            'total_pages' => $lastPage,
            'total_users' => $total,
            'count'       => $perPage,
            'links'       => [
                'next_url' => $this->nextPageUrl(),
                'prev_url' => $this->previousPageUrl(),
            ],
            'users'       => UserResource::collection($this->collection),
        ];
    }
}
