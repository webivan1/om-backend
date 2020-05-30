<?php

use App\Model\Region\Entities\Region\Region;
use App\Model\User\Entities\User\User;
use Illuminate\Database\Seeder;
use App\Model\Event\Entities\Event\Event;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\FactorySeeder;
use LaravelDoctrine\ORM\Facades\EntityManager;

class EventSeeder extends Seeder
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        $this->generate();
    }

    public function run()
    {
        $collection = FactorySeeder::new()->factory(Event::class, 50);
        $collection->each(function (Event $event) {
            $this->em->persist($event);
        });

        $this->em->flush();
    }

    public function generate()
    {
        FactorySeeder::new()->define(Event::class, function (\Faker\Generator $faker) {
            $repo = EntityManager::getRepository(Region::class);
            $region = collect($repo->findAll())->random();
            $user = collect(EntityManager::getRepository(User::class)->findAll())->random();

            $start = (new \DateTime())->modify('+ ' . mt_rand(1, 8) . ' days');
            $end = clone $start;
            $end->modify('+ ' . mt_rand(1, 8) . ' hours');

            $model = new Event();
            $model->setTitle($faker->text(100));
            $model->setDescription($faker->text(200));
            $model->setUser($user);
            $model->setRegion($region);
            $model->setCreatedAt(new \DateTime());
            $model->setUpdatedAt(new \DateTime());
            $model->setStartAt($start);
            $model->setFinishAt($end);
            $model->setStatus($faker->randomElement([
                Event::STATUS_APPROVED,
                Event::STATUS_REJECT,
                Event::STATUS_MODERATION,
                Event::STATUS_DRAFT
            ]));

            return $model;
        });
    }
}
