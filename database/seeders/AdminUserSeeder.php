<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'support@maharatnet.com');
        $password = env('ADMIN_PASSWORD');

        $isNew = ! User::where('email', $email)->exists();

        if (! $password) {
            $password = $isNew ? Str::password(16) : null;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'is_active' => true,
                'email_verified_at' => now(),
                ...($password ? ['password' => Hash::make($password)] : []),
            ]
        );

        $user->syncRoles(['Super Admin']);

        if ($isNew && $password) {
            $this->command?->warn("Admin account created — email: {$email} / password: {$password}");
        }
    }
}
