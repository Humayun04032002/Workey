<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // এই লাইনটি যোগ করুন
    protected $fillable = ['key', 'value'];
}