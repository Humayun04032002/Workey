<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    // arrived_at কলামটি এখানে যুক্ত করা হয়েছে
    protected $fillable = [
        'job_id', 
        'worker_id', 
        'status', 
        'arrived_at',
        'completed_at'
    ];

    /**
     * কাস্টিং: arrived_at এবং completed_at কে কার্বন অবজেক্ট হিসেবে ব্যবহার করার জন্য
     */
    protected $casts = [
        'arrived_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // রিলেশনশিপ: একটি আবেদন একটি নির্দিষ্ট জবের জন্য
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    // রিলেশনশিপ: আবেদনকারী একজন ইউজার (ওয়ার্কার)
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}