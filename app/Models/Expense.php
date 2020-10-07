<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
