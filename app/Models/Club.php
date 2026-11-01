<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Club extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'advisor_id',
        'created_by',
        'status',
    ];

    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function memberships()
    {
        return $this->hasMany(ClubMembership::class);
    }
}