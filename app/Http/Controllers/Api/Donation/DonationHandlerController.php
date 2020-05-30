<?php

namespace App\Http\Controllers\Api\Donation;

use App\Model\Donation\UseCases\Donation\DonationService;
use Illuminate\Http\Request;

class DonationHandlerController
{
    private DonationService $service;

    public function __construct(DonationService $service)
    {
        $this->service = $service;
    }

    public function check(string $source, Request $request)
    {
        try {
            $model = $this->service->check($source, $request->all());

            if ($response = $this->service->responseText()) {
                return response($response);
            }

            if ($model->isRejected()) {
                return view('donation.handler.failed', compact('model'));
            } else if ($model->isApproved()) {
                return view('donation.handler.success', compact('model'));
            } else {
                return view('donation.handler.wait', compact('model'));
            }
        } catch (\DomainException $e) {
            abort(404, $e->getMessage());
        }
    }

    public function failed(string $source, Request $request)
    {
        try {
            $model = $this->service->failed($source, $request->all());

            return view('donation.handler.failed', compact('model'));
        } catch (\DomainException $e) {
            abort(404, $e->getMessage());
        }
    }
}
