<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    public const DEFAULT_TYPE = "TICKET";
    public const DEFAULT_PER_PAGE = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'type',
        'description',
        'value',
        'employee_id'
    ];
}
