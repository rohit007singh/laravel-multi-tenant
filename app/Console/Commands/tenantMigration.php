<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Services\TenantDatabaseService;

class tenantMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant-migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $this->processMigrateCommand($user);
        }

    }

    private function processMigrateCommand(User $user)
    {
        // Configure the tenant connection for the current user
        app(TenantDatabaseService::class)->configureTenantConnection($user);

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/tenant/migrations',
            '--force' => true
        ]);
    }
}
