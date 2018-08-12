<?php

namespace Bitfumes\Multiauth\Console\Commands;

use Illuminate\Console\Command;
use Bitfumes\Multiauth\Model\Admin;
use Bitfumes\Multiauth\Model\Role;

class RoleCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiauth:seed {--r|role=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed one super admin for multiauth package
                            {--role= : Give any role name to create new role}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rolename = $this->option('role');
        $role = Role::whereName($rolename)->first();

        $admin = factory(Admin::class)
            ->create(['email' => 'admin@gmail.com', 'name' => 'Super Admin']);
        $admin->each(function ($admin) use ($role,$rolename) {
            if (!$role) {
                $role = factory(Role::class)->create(['name' => $rolename]);
            }
            $admin->roles()->attach($role);
        });
        $this->info("You have created an admin name '{$admin->name}' with role of '{$admin->roles->first()->name}' ");
    }
}