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

        $expenses = DB::table($expenseTable)
                    ->select(DB::raw("{$expenseTable}.category_id, SUM({$expenseTable}.amount) AS amount_sum"))
                    ->whereBetween("{$expenseTable}.date", [$dateStart, $dateEnd])
                    ->groupBy(DB::raw("{$expenseTable}.category_id"));

        $orderedWithCategories = DB::table($categoryTable)
                                ->joinSub($expenses, 'expenses', function ($join) use ($categoryTable) {
                                    $join->on("{$categoryTable}.id", '=', 'expenses.category_id');
                                })
                                ->orderBy("expenses.amount_sum", 'desc');

        return $orderedWithCategories->get();
    }
}
