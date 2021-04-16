<?php

namespace App\Console\Commands\Elastic;

use ElasticMigrations\Facades\Index;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

/**
 * Drop an index from the Elasticsearch instance.
 *
 * @package App\Console\Commands\Elastic
 * @since 1.0.0
 */
class DropIndex extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     * @since 1.0.0
     */
    protected $signature = 'elastic:index:drop {index : The search index name without the Scout prefix}';

    /**
     * The console command description.
     *
     * @var string
     * @since 1.0.0
     */
    protected $description = 'Drop an index from the Elasticsearch instance by its index name';

    /**
     * Create a new command instance.
     *
     * @since 1.0.0
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
     * @since 1.0.0
     */
    public function handle(Client $client)
    {
        if (!$this->confirmToProceed()) {
            return 1;
        }

        $index = $this->argument('index');
        $exists = $client->indices()->exists([
            'index' => env('SCOUT_PREFIX', '').$index,
        ]);

        if (!$exists) {
            $this->warn('That index does not exist');
            return 1;
        } else {
            Index::dropIfExists($index);
            $this->info('Successfully dropped index');
            return 0;
        }
    }
}
