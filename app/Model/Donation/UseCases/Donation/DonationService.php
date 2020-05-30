<?php

namespace App\Model\Donation\UseCases\Donation;

use App\Model\Common\Services\Payment\Drivers\Exceptions\FailPaymentException;
use App\Model\Common\Services\Payment\Drivers\Exceptions\FailResponseTextPaymentException;
use App\Model\Common\Services\Payment\Drivers\Exceptions\SuccessPaymentException;
use App\Model\Common\Services\Payment\Drivers\Exceptions\SuccessResponseTextPaymentException;
use App\Model\Common\Services\Payment\PaymentDriver;
use App\Model\Common\Services\Payment\PaymentService;
use App\Model\Common\Services\Payment\TransactionConfig;
use App\Model\Donation\Entities\Donation\Donation;
use App\Model\Donation\Entities\Donation\Values\Amount;
use App\Model\Donation\Entities\Donation\Values\Email;
use App\Model\Donation\Entities\Donation\Values\MessageText;
use App\Model\Donation\Entities\Donation\Values\Source;
use App\Model\Donation\Entities\Donation\Values\Username;
use App\Model\Donation\Repositories\DonationRepository;
use App\Model\Event\Entities\Event\Event;
use App\Model\Event\Repositories\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class DonationService
{
    private EntityManagerInterface $em;

    /** @var ObjectRepository|EventRepository */
    private ObjectRepository $repoEvent;

    /** @var ObjectRepository|DonationRepository */
    private ObjectRepository $repoDonate;

    private ?string $responseText;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repoEvent = $this->em->getRepository(Event::class);
        $this->repoDonate = $this->em->getRepository(Donation::class);
    }

    public function create(DonationCreateDto $dto): Donation
    {
        /** @var Event|null $event */
        $event = $dto->eventId ? $this->repoEvent->find($dto->eventId) : null;
        $email = $dto->email ? new Email($dto->email) : null;
        $message = $dto->message ? new MessageText($dto->message) : null;
        $amount = new Amount($dto->amount);
        $username = new Username($dto->username);
        $source = new Source($dto->source);

        $donation = Donation::new($event, $amount, $username, $email, $message, $source);

        $this->em->persist($donation);
        $this->em->flush();

        return $donation;
    }

    private function createTransactionConfig(Donation $donation): TransactionConfig
    {
        return new TransactionConfig((string)$donation->getId(), $donation->getAmount(), 'Donation');
    }

    public function getPayUrl(Donation $donation, string $successUrl, string $failUrl): ?string
    {
        if ($donation->isApproved()) {
            throw new \DomainException('This payment has already been paid.');
        }

        $service = new PaymentService(new PaymentDriver($donation->getSource()));

        $config = $this->createTransactionConfig($donation);

        return $service->createTransaction($config, $successUrl, $failUrl);
    }

    public function check(string $source, array $request): Donation
    {
        $service = new PaymentService(new PaymentDriver($source));

        $donation = $this->repoDonate->getOne(
            $service->getTransactionId($request)
        );

        if ($donation->isApproved()) {
            throw new \DomainException('This donation has been paid');
        }

        try {
            $config = $this->createTransactionConfig($donation);
            $service->checkTransaction($config, $request);
        } catch (FailPaymentException $e) {
            $donation->toReject();
        } catch (SuccessPaymentException $e) {
            $donation->toApprove();
        } catch (FailResponseTextPaymentException $e) {
            $donation->toReject();
            $this->responseText = $e->getMessage();
        } catch (SuccessResponseTextPaymentException $e) {
            $donation->toApprove();
            $this->responseText = $e->getMessage();
        } finally {
            $this->save($donation);

            // @todo add events (add message)
        }

        return $donation;
    }

    public function failed(string $source, array $request): Donation
    {
        $service = new PaymentService(new PaymentDriver($source));

        $donation = $this->repoDonate->getOne(
            $service->getTransactionId($request)
        );

        $donation->toReject();

        $this->em->persist($donation);
        $this->em->flush();

        return $donation;
    }

    private function save(Donation $donation)
    {
        $this->em->persist($donation);
        $this->em->flush();
    }

    public function responseText(): ?string
    {
        return $this->responseText;
    }
}
