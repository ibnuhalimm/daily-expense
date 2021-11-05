<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function scopeSearch($query, $keyword = null)
    {
        if (!empty($keyword)) {
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            });
        }

        return;
    }


    public static function reportExpenseCategoryDateRange($dateStart, $dateEnd)
    {
        $expenseTable = (new Expense())->getTable();
        $categoryTable = (new Category())->getTable();

        $query = DB::table($expenseTable)
                ->select(DB::raw("
                        {$expenseTable}.category_id,
                        COUNT({$expenseTable}.id) AS total,
                        SUM({$expenseTable}.amount) AS total_amount,
                        {$categoryTable}.name AS category_name"))
                ->join($categoryTable, "{$expenseTable}.category_id", '=', "{$categoryTable}.id")
                ->whereBetween("{$expenseTable}.date", [$dateStart, $dateEnd])
                ->groupBy(DB::raw("{$expenseTable}.category_id, {$categoryTable}.name"))
                ->orderBy("{$categoryTable}.name", 'asc');

        return $query->get();
    }
}
