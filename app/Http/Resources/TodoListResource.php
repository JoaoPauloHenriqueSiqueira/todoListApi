<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $obj = [
            'id' => $this->id,
            'active' => $this->active,
            'has_timer' => $this->has_timer,
            'title' => $this->title,
            'description' => $this->description,
            'created_at' => $this->created_at,
            "total_time" => false
        ];

        if ($this->has_timer && !$this->active) {
            $options = [
                'join' => ', ',
                'parts' => 2,
                'syntax' => CarbonInterface::DIFF_ABSOLUTE,
            ];
            $obj['total_time'] = Carbon::parse($this->updated_at)->diffForHumans(Carbon::parse($this->created_at), $options);
        }

        return $obj;
    }
}
