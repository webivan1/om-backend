<?php

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Database\Seeder;
use App\Model\Region\Entities\Region\Region;
use Illuminate\Support\Str;
use App\Model\Region\Entities\Region\Values\ {
    Label,
    LatLng,
    Slug,
    Distance,
    Timezone
};

class RegionSeeder extends Seeder
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function run(): void
    {
        foreach ($this->regions() as $region) {
            $model = Region::create(
                new Label($region['label']),
                new Slug(Str::slug($region['label'])),
                new LatLng($region['lat'], $region['lng']),
                new Distance($region['distance']),
                new Timezone($region['timezone'])
            );

            $this->em->persist($model);
        }

        $this->em->flush();
    }

    public function regions(): array
    {
        return [
            [
                'label' => 'Москва',
                'lat' => 55.7522200,
                'lng' => 37.6155600,
                'distance' => 90000,
                'timezone' => 'Europe/Moscow'
            ],
            [
                'label' => 'Санкт-Петербург',
                'lat' => 59.9386300,
                'lng' => 30.3141300,
                'distance' => 60000,
                'timezone' => 'Europe/Moscow'
            ]
        ];
    }
}
