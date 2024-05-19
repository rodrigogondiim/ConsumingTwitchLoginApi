<?php

namespace App\Http\Resources;

use App\Models\Friend;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowUserResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'picture' => $this->picture,
            'created_at' => $this->created_at,
            $this->mergeWhen(Friend::itIsMyFriend($this->id)->doesntExist(), [
                'was_my_solicitation' => Friend::byMeSolicited($this->id)->exists(),
                'have_solicitation' => Friend::outerSolicited($this->id)->first()?->id
            ]),
            'it_is_my_friend' => Friend::itIsMyFriend($this->id)->exists()
        ];
    }
}
