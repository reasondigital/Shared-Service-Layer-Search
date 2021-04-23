<?php

namespace App\Console\Commands\Token;

use App\Models\User;
use Illuminate\Console\Command;
use const PHP_EOL;

/**
 * Command to list API access tokens available in this application by user.
 *
 * @package App\Console\Commands\Token
 * @since   1.0.0
 */
class Show extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:list {emailAddress : The user account to show tokens for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List API access tokens available in this application by user';

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
     * @return int
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

        if ($user->tokens()->count() === 0) {
            $this->warn('This user has no access tokens attributed to their account');
            return 0;
        }

        $headers = [
            'ID',
            'Name',
            'Abilities',
            'Last used',
        ];

        $rows = [];
        foreach ($user->tokens()->get() as $token) {
            $lastUsed = $token->last_used_at;
            if (is_null($lastUsed)) {
                $lastUsed = 'Never';
            }

            // Extra line break (PHP_EOL) so that each token has a bit of space in the table
            $rows[] = [
                $token->id,
                $token->name,
                implode(PHP_EOL, $token->abilities) . PHP_EOL,
                $lastUsed,
            ];
        }

        $this->table($headers, $rows);
        return 0;
    }
}
