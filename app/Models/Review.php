<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    // job_id টিও এখানে যোগ করে নিন কারণ এটি রিভিউ ট্র্যাক করতে জরুরি
    protected $fillable = ['worker_id', 'employer_id', 'job_id', 'rating', 'comment'];

    /**
     * যে কর্মী রিভিউটি পাচ্ছেন
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    /**
     * যে মালিক (Employer) রিভিউটি দিচ্ছেন
     */
    public function employer(): BelongsTo
    {
        // এটিই আপনার এররের মূল সমাধান
        return $this->belongsTo(User::class, 'employer_id');
    }

    /**
     * যে কাজের (Job) প্রেক্ষিতে রিভিউটি দেওয়া হচ্ছে
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}