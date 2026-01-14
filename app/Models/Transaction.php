<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * যে ফিল্ডগুলো মাস অ্যাসাইনমেন্ট করা যাবে।
     */
    protected $fillable = [
        'user_id',
        'amount',
        'type',    // 'deposit' (টাকা ঢুকলে) অথবা 'debit' (অ্যাপ্লাই ফি কাটলে)
        'purpose', // যেমন: 'Job Application Fee', 'Wallet Recharge'
        'status',  // 'completed', 'pending', 'failed'
    ];

    /**
     * ট্রানজেকশনটি কোন ইউজারের তা নির্ধারণ করে।
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}