<?php

namespace App\Http\Requests\Api\Profile\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:150',
            'description' => 'required|string|min:10|max:255',
            'region' => 'required|integer',
            'startAt' => 'required|date:Y-m-d H:i:s',
            'duration' => 'required|integer|min:3600'
        ];
    }
}
