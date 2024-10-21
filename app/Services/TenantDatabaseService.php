<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class TenantDatabaseService
{
    public function createTenantDatabase(User $user)
    {
        try {
            // Create database
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$user->db}`");

            // Create database user - Fixed the syntax error here
            $password = $user->db_password;
            DB::statement("CREATE USER IF NOT EXISTS '{$user->db_user}'@'localhost' IDENTIFIED BY '{$password}'");

            // Grant privileges
            DB::statement("GRANT ALL PRIVILEGES ON `{$user->db}`.* TO '{$user->db_user}'@'localhost'");
            DB::statement("FLUSH PRIVILEGES");

            // Configure and run migrations for the new database
            $this->configureTenantConnection($user);
            $this->runTenantMigrations();

            return true;
        } catch (Exception $e) {
            Log::error('Failed to create tenant database: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteTenantDatabase(User $user)
    {
        try {
            // Drop database
            DB::statement("DROP DATABASE IF EXISTS `{$user->db}`");

            // Drop user
            DB::statement("DROP USER IF EXISTS '{$user->db_user}'@'localhost'");
            DB::statement("FLUSH PRIVILEGES");

            return true;
        } catch (Exception $e) {
            Log::error('Failed to delete tenant database: ' . $e->getMessage());
            throw $e;
        }
    }

    public function configureTenantConnection(User $user)
    {
        Config::set('database.connections.tenant', [
            'driver' => 'mysql',
            'host' => env('DB_HOST_TENANT', '127.0.0.1'),
            'database' => $user->db,
            'username' => $user->db_user,
            'password' => $user->db_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ]);

        DB::purge('tenant');
    }

    public function runTenantMigrations()
    {
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/tenant/migrations',
            '--force' => true
        ]);
    }
}