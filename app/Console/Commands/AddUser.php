<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-user {email?} {password?} {username?} {role?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user to pass login validation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get input from arguments or ask the user
        $email = $this->argument('email') ?? $this->ask('Enter user email');
        $password = $this->argument('password') ?? $this->secret('Enter user password');
        $username = $this->argument('username') ?? $this->ask('Enter username');
        $role = $this->argument('role') ?? $this->ask('Enter user role');

        // Simple check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        // Create the user
        User::create([
            'name' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
        ]);

        $this->info("User created successfully!");
        $this->line("Email: <comment>{$email}</comment>");

        return 0;
    }
}