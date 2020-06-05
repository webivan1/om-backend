<?php

namespace App\Console\Commands\Role;

use Illuminate\Console\Command;

class RoleAllCreateCommand extends Command
{
    protected $signature = 'role:create:all';
    protected $description = 'Create all roles';


    public function handle()
    {
        $roles = ['admin', 'organizer', 'user'];

        foreach ($roles as $role) {
            $this->call('role:create', ['name' => $role]);
        }
    }
}
