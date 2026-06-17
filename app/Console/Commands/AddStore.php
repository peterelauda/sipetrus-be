<?php

namespace App\Console\Commands;

use App\Models\Store;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AddStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new store';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('Store name');

        if (Store::where('name', $name)->exists()) {
            $this->error('Store already exists');
            return 1;
        }

        $password = $this->secret('Store password');

        Store::create([
            'name' => $name,
            'password' => Hash::make($password),
        ]);

        $this->info('Store created successfully');

        return 0;
    }
}
