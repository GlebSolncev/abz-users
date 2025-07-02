<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{

    public static $wrap = null;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $position = $this->whenLoaded('position');

        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'email'                  => $this->email,
            'phone'                  => $this->phone,
            /**
             * I think will be better, if i using:
             *
             * 'position' => PositionResource::make($this->whenLoaded('position'))
             *
             * And work with position like a object for ex position.name or position.id
             * But this is not according to the TS
             */
            'position'               => $position->name,
            'position_id'            => $position->id,
            'registration_timestamp' => Carbon::parse($this->created_at)->timestamp,
            'photo'                  => Storage::disk('public')->url($this->photo),
        ];

    }
}
