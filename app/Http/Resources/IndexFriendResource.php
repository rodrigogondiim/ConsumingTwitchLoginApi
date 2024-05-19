<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexFriendResource extends JsonResource
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            $this->mergeWhen($this->user->id != auth()->user()->id, $this->user),
            $this->mergeWhen($this->friend->id != auth()->user()->id, $this->friend),
            'status' => $this->status
        ];
    }
}
