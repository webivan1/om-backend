<?php

namespace App\Console\Commands\Role;

use App\Model\User\UseCases\Role\RoleService;
use Illuminate\Console\Command;

class RoleCreateCommand extends Command
{
    protected $signature = 'role:create {name}';
    protected $description = 'Create new role';


    public function handle(RoleService $service)
    {
        try {
            $roleName = $this->argument('name');
            $service->create($roleName);
            $this->alert('Role has been created');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
