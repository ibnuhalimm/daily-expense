<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Expense extends Model
{
    use HasFactory;

    /**
     * Mass fillable field
     *
     * @var array
     */
    protected $fillable = [
        'date', 'description', 'amount'
    ];

    /**
     * Casts some fields
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date'
    ];

    /**
     * Get category of expense
     *
     * @return mixed
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Query to get total expense today
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param Date $from
     * @param Date $to
     * @return double
     */
    public function scopeTotalExpense($query, $from = null, $to = null)
    {
        if (empty($from) OR empty($to)) {
            $from = $to = date('Y-m-d');
        }

        return $query->whereBetween('date', [ $from, $to ])->sum('amount');
    }
}
