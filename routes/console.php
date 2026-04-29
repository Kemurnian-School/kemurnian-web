<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create {name} {email} {password}', function () {
    $name = $this->argument('name');
    $email = $this->argument('email');
    $password = $this->argument('password');

    if (User::where('email', $email)->exists()) {
        $this->error('User already exists with that email.');
        return self::FAILURE;
    }

    User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'is_admin' => true,
    ]);

    $this->info('Admin user created.');
    return self::SUCCESS;
})->purpose('Create an admin user');
