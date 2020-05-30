<?php

namespace App\Http\Requests\Api\Donation;

use App\Model\Common\Services\Payment\PaymentDriver;
use App\Model\Donation\Entities\Donation\Donation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DonationCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eventId' => 'exists:events,id|nullable',
            'amount' => 'required|numeric|min:' . Donation::MIN_PRICE,
            'username' => 'required|string|min:2|max:50',
            'email' => 'email|nullable',
            'message' => 'string|max:255|min:1|nullable',
            'source' => [
                'required',
                Rule::in(PaymentDriver::allowDrivers())
            ]
        ];
    }
}
