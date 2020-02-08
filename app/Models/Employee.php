<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public const DEFAULT_PER_PAGE = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'identification',
        'jobRole'
    ];

    /**
     * Refunds Relationship
     */
    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
}
