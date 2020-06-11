<?php

namespace App\Http\Controllers\Api\Donation;

use App\Http\Requests\Api\Donation\DonationCreateRequest;
use App\Model\Donation\UseCases\Donation\DonationCreateDto;
use App\Model\Donation\UseCases\Donation\DonationService;

class DonationCreateController
{
    public function create(DonationCreateRequest $request, DonationService $service)
    {
        $dto = new DonationCreateDto();
        $dto->source = $request->input('source');
        $dto->eventId = $request->input('eventId');
        $dto->amount = (float)$request->input('amount');
        $dto->username = (string)$request->input('username');
        $dto->email = $request->input('email');
        $dto->message = $request->input('message');

        try {
            $model = $service->create($dto);

            return [
                'status' => 'success',
                'donationId' => $model->getId(),
                'redirectUrl' => route('donation.payment', $model->getId())
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}
