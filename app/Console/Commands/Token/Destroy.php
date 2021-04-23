<?php

namespace App\Console\Commands\Token;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;

/**
 * Command to permanently delete API access tokens from this application.
 *
 * @package App\Console\Commands\Token
 * @since   1.0.0
 */
class Destroy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:destroy
                            {tokenId : Use `php artisan token:list` to see tokens and their IDs}
                            {emailAddress : The user account to delete the token from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete API access tokens from this application';

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
     * Forcing both token ID and email should minimise typos causing accidental
     * deletes.
     *
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        $email = $this->argument('emailAddress');

        /** @var User $user */
        $user = User::where('email', '=', $email)->first();
        if (is_null($user)) {
            $this->error('No user exists by that email address');
            return 1;
        }

        $tokenId = $this->argument('tokenId');
        $token = $user->tokens()->where('id', '=', $tokenId)->first();
        if (is_null($token)) {
            $this->error('No token by that ID exists under the given user');
            return 1;
        }

        $token->delete();
        $this->info('Token successfully deleted');

        return 0;
    }
}
