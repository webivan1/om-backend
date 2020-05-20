<?php

namespace App\Console\Commands\Region;

use App\Model\Region\UseCases\Region\RegionCreateDto;
use App\Model\Region\UseCases\Region\RegionService;
use Illuminate\Console\Command;

class RegionCreateCommand extends Command
{
    protected $signature = 'region:create {label} {latLng} {distance}';
    protected $description = 'Create new region';

    public function handle(RegionService $service)
    {
        $dto = new RegionCreateDto();

        [$lat, $lng] = array_map('floatval', explode(',', $this->argument('latLng')));

        $dto->label = $this->argument('label');
        $dto->lat = (float) $lat;
        $dto->lng = (float) $lng;
        $dto->distance = (int) $this->argument('distance');

        try {
            $service->create($dto);
            $this->alert('The Region has been created');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
