<?php

namespace App\Http\Resources\API\V1\Leads;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => LeadResource::collection($this->collection)->resolve($request),
            'meta' => [
                'current_page' => $this->currentPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
}
