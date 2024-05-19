<?php

namespace App\Http\Requests;

use App\Enum\FriendStatus;
use Illuminate\Foundation\Http\FormRequest;

class FriendshipRequest  extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->friend->whereStatus(FriendStatus::PENDENT)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'accept' => 'required|bool'
        ];
    }
}
