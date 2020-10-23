<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show dashboard page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = [
            'today_expense' => Expense::totalExpense(date('Y-m-d'), date('Y-m-d')),
            'this_month_expense' => Expense::totalExpense(date('Y-m-01'), date('Y-m-t')),
            'daily_average' => round(Expense::totalExpense(date('Y-m-01'), date('Y-m-t')) / date('j'), 0)
        ];

        return view('dashboard', $data);
    }
}
