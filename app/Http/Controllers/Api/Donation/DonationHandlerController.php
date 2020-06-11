<?php

namespace App\Http\Controllers\Api\Donation;

use App\Events\Donation\DonationApprovedBroadcast;
use App\Events\Donation\DonationFailedBroadcast;
use App\Events\Donation\DonationWaitBroadcast;
use App\Model\Donation\Entities\Donation\Donation;
use App\Model\Donation\UseCases\Donation\DonationService;
use Illuminate\Http\Request;

class DonationHandlerController
{
    private DonationService $service;

    public function __construct(DonationService $service)
    {
        $this->service = $service;
    }

    public function check(Donation $donation, Request $request)
    {
        try {
            $this->service->check($donation, $request->all());

            if ($response = $this->service->getResponseText()) {
                return response($response);
            }

            if ($donation->isRejected()) {
                broadcast(new DonationFailedBroadcast($donation));
            } else if ($donation->isApproved()) {
                broadcast(new DonationApprovedBroadcast($donation));
            } else {
                broadcast(new DonationWaitBroadcast($donation));
            }

            return view('donation.handler.closed');
        } catch (\DomainException $e) {
            abort(404, $e->getMessage());
        }
    }

    public function failed(Donation $donation, Request $request)
    {
        try {
            $this->service->failed($donation, $request->all());

            broadcast(new DonationFailedBroadcast($donation));

            return view('donation.handler.closed');
        } catch (\DomainException $e) {
            abort(404, $e->getMessage());
        }
    }
}
