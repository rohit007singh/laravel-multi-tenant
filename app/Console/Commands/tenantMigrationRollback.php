<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use App\Services\TenantDatabaseService;

class tenantMigrationRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant-migration-rollback {step?}';

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
        $step = $this->argument('step');
        $users = User::all();
        foreach ($users as $user) {
            $this->processMigrateRollbackCommand($user, $step);
        }
    }

    private function processMigrateRollbackCommand(User $user, $step)
    {
        // Configure the tenant connection for the current user
        app(TenantDatabaseService::class)->configureTenantConnection($user);
        //if step is null than rollback all migrations
        $options = [
            '--database' => 'tenant',
            '--path' => 'database/tenant/migrations',
            '--force' => true,
        ];

        if ($step !== null) {
            $options['--step'] = $step;
        }

        Artisan::call('migrate:rollback', $options);
    }

}
