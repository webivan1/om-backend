<?php

namespace App\Http\Controllers\Api\Donation;

use App\Model\Donation\Entities\Donation\Donation;
use App\Model\Donation\UseCases\Donation\DonationService;

class DonationPaymentController
{
    public function url(Donation $donation, DonationService $service)
    {
        try {
            $redirectUrl = $service->getPayUrl(
                $donation,
                route('donation.handler', [
                    'donation' => $donation->getId()
                ]),
                route('donation.handler.failed', [
                    'donation' => $donation->getId()
                ])
            );

            return redirect()->to($redirectUrl);
        } catch (\DomainException $e) {
            abort(404, $e->getMessage());
        }
    }
}
