<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Assignment එකේ Task 01 හි සඳහන් fields මෙහි ඇතුළත් කළ යුතුය.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        
    ];
}