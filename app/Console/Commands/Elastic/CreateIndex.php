<?php

namespace App\Console\Commands\Elastic;

use ElasticMigrations\MigrationInterface;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Filesystem\Filesystem;

/**
 * Migrate an index into the Elasticsearch instance using the given migration
 * class.
 *
 * @package App\Console\Commands\Elastic
 * @since 1.0.0
 */
class CreateIndex extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic:index:create {class : The class name of the Elastic index migration class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a single Elasticsearch index by its migration class name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (env('SCOUT_DRIVER') !== 'elastic') {
            return;
        }

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $filesystem)
    {
        if (!$this->confirmToProceed()) {
            return 1;
        }

        $migrationClass = $this->argument('class');
        $migrationsDir = rtrim(config('elastic.migrations.storage_directory', ''), '/');

        // Search Elastic migrations directory, exit if empty
        $files = $filesystem->glob($migrationsDir . '/*_*.php');
        if (empty($files)) {
            $this->warn("No migrations found in $migrationsDir");
            return 0;
        }

        // Load up the migration files so we can check against the class names
        foreach ($files as $file) {
            require_once $file;
        }

        if (!class_exists($migrationClass)) {
            $this->error('The given migration class was not found');
            return 1;
        }

        try {
            /** @var MigrationInterface $migration */
            $migration = new $migrationClass;
            $migration->up();
        } catch (BadRequest400Exception $e) {
            $errorData = json_decode($e->getMessage());
            $this->error($errorData->error->root_cause[0]->reason);
            return 1;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $this->info('Successfully created index');
        return 0;
    }
}
