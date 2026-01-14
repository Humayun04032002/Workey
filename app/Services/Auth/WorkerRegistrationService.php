<?php
namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WorkerRegistrationService
{
    public function createBasicAccount(array $data)
    {
        return User::create([
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'worker',
        ]);
    }

    public function updateProfile(User $user, array $data)
    {
        return $user->update([
            'category' => $data['category'],
            'expected_wage' => $data['expected_wage'],
            'work_area' => $data['work_area'] ?? null,
        ]);
    }

    public function uploadVerification(User $user, $photo)
    {
        $path = $photo->store('workers/photos', 'public');
        return $user->update(['profile_photo' => $path]);
    }
}