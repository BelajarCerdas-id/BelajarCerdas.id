<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class test extends Model
{
     use HasFactory, SoftDeletes; // Include this line

    protected $fillable = ['nama', 'email']; // Example fields

    
}
