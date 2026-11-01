<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClubMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'student_id',
        'status',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}