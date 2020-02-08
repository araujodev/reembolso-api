<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use SoftDeletes;

    public const DEFAULT_TYPE = "TICKET";
    public const DEFAULT_PER_PAGE = 10;
    public const STATUS_OPENED = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_CANCELED = 0;

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
        'employee_id',
        'status',
        'receipt'
    ];

    /**
     * Employee Relationship
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
