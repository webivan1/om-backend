<?php

namespace App\Model\Donation\Repositories;

use App\Model\Donation\Entities\Donation\Donation;

class DonationRepository extends \Doctrine\ORM\EntityRepository
{
    public function getOne(int $id): Donation
    {
        $donation = $this->find($id);

        if (empty($donation)) {
            throw new \DomainException('This donation is not found');
        }

        return $donation;
    }
}
