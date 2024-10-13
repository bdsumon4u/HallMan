<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sid' => $this->sid,
            'name' => $this->name,
            'department' => $this->department,
            'session' => $this->session,
            'year' => $this->year,
            'hall_id' => $this->hall_id,
            'image' => filter_var($this->image, FILTER_VALIDATE_URL) ? $this->image : asset("storage/{$this->image}"),
        ];
    }
}
