<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$request->id,
            'status' => $request->status,
            'title' => $request->title,
            'description' => $request->description,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at
            ];
    }
}
