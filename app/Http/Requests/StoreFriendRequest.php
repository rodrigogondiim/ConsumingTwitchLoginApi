<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Friend;

class StoreFriendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Friend::byMeSolicited($this->user->id)->doesntExist() and auth()->user()->id !== $this->user->id and Friend::itIsMyFriend($this->user->id)->doesntExist();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
