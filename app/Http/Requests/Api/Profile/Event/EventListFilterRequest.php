<?php

namespace App\Http\Requests\Api\Profile\Event;

use App\Model\Event\Entities\Event\Event;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventListFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'region' => 'nullable|integer',
            'title' => 'nullable|string',
            'startDate' => 'nullable|date_format:Y-m-d',
            'status' => [
                'nullable',
                'string',
                Rule::in([
                    Event::STATUS_APPROVED,
                    Event::STATUS_DRAFT,
                    Event::STATUS_REJECT,
                    Event::STATUS_MODERATION
                ])
            ],
        ];
    }
}
