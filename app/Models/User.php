<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'password',
        'role',
        'category',
        'expected_wage',
        'business_name',
        'address',
        'profile_photo',
        'email',
        'balance',
        'is_verified', 
        'is_banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'is_verified' => 'boolean', 
            'is_banned' => 'boolean',
        ];
    }

    // --- রিলেশনসমূহ ---

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->orderBy('created_at', 'desc');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'worker_id');
    }

    public function reportsReceived(): HasMany
    {
        return $this->hasMany(Report::class, 'reported_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'worker_id');
    }

    public function jobsAsWorker(): HasMany
    {
        return $this->hasMany(Application::class, 'worker_id')->where('status', 'completed');
    }

    public function postedJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'employer_id');
    }

    // --- হেল্পার মেথডসমূহ ---

    public function isWorker(): bool
    {
        return $this->role === 'worker';
    }

    public function isEmployer(): bool
    {
        return $this->role === 'employer';
    }

    /**
     * কর্মীর গড় রেটিং বের করার মেথড
     * ব্লেড ফাইলে $worker->averageRating() হিসেবে কল করা যাবে
     */
    public function averageRating()
    {
        $avg = $this->reviews()->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }

    /**
     * এক্সেসর (Accessor) হিসেবে রেটিং পাওয়া
     * ব্লেড ফাইলে $worker->rating হিসেবে কল করা যাবে
     */
    public function getRatingAttribute()
    {
        return $this->averageRating();
    }
}