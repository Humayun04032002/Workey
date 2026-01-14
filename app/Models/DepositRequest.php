<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositRequest extends Model
{
    // ডাটাবেসে যে কলামগুলোতে ডাটা সেভ করার অনুমতি দিতে চান
    protected $fillable = [
        'user_id',
        'amount',
        'method',
        'sender_number',
        'transaction_id',
        'status',
        'admin_note'
    ];

    // ইউজারের সাথে রিলেশন (অ্যাডমিন প্যানেলে নাম দেখানোর জন্য)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}