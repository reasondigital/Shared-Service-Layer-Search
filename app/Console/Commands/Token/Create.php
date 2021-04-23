<?php

namespace App\Console\Commands\Token;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Command to create API access tokens for this application
 *
 * @package App\Console\Commands\Token
 * @since   1.0.0
 */
class Create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "token:create
                            {name : A name for the token, e.g 'Mobile App'. This is strictly for reference}
                            {accessLevel : Options are 'admin', 'write' and 'read'}
                            {emailAddress : The token will be attributed to this address. An account will be created in the application for this user if one doesn't already exist}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create API access tokens for this application';

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
     * todo Check token name against user to keep token names unique
     *
     * @return int
     */
    public function handle(): int
    {
        $tokenName = $this->argument('name');
        $level = $this->argument('accessLevel');
        $email = $this->argument('emailAddress');

        /** @var User $user */
        $user = User::firstOrCreate(
            [
                'email' => $email,
            ],
            [
                'name' => $email,
                'password' => Hash::make(Str::random()),
            ]
        );

        $token = $user->createToken($tokenName);

        $this->info('Copy and save this token in a secure place.');
        $this->info('The API will not display the token again after generation.');
        $this->newLine();
        $this->info('The new access token:');
        $this->info($token->plainTextToken);

        return 0;
    }
}
