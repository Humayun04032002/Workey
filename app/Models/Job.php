<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id', 
        'title', 
        'category', 
        'wage', 
        'wage_type',    
        'payment_type',  // নতুন: 'cash' অথবা 'in_app'
        'work_date',    
        'start_time',   
        'end_time',     
        'duration',     
        'worker_count', 
        'location_name', 
        'lat', 
        'lng', 
        'description', 
        'status'        // 'open', 'filled', 'completed'
    ];

    /**
     * কাস্টিং: ডাটাবেজ থেকে ডাটা আসার সময় সঠিক ফরম্যাটে পাওয়ার জন্য
     */
    protected $casts = [
        'work_date' => 'date',
        'wage' => 'decimal:2',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
    ];

    /**
     * রিলেশনশিপ: একটি জব একজন এমপ্লয়ারের অধীনে থাকে
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    /**
     * রিলেশনশিপ: একটি জবে অনেকগুলো আবেদন (Applications) থাকতে পারে
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_id');
    }

    /**
     * এক্সেসর: কতজন কর্মী এখন পর্যন্ত এক্সেপ্ট হয়েছে
     */
    public function getAcceptedCountAttribute()
    {
        return $this->applications()->whereIn('status', ['accepted', 'arrived', 'payment_pending', 'completed'])->count();
    }

    /**
     * হেল্পার: জবটি কি এখনও খালি আছে?
     */
    public function isAvailable()
    {
        return $this->status === 'open' && $this->accepted_count < $this->worker_count;
    }
    
    /**
     * হেল্পার: পেমেন্ট মেথড চেক
     */
    public function isCashPayment()
    {
        return $this->payment_type === 'cash';
    }
}