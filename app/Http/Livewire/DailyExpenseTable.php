<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Expense;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class DailyExpenseTable extends Component
{
    use WithPagination;

    /**
     * Define properties
     *
     * @var mixed
     */
    public $is_create_modal_show;
    public $is_delete_modal_show;

    public $filter_category_id;
    public $filter_date_range;

    public $is_edit_mode;
    public $expense_id;
    public $category_id;
    public $category_name;

    public $store_date;
    public $description;
    public $amount;


    protected $rules = [
        'category_id' => [
            'required'
        ],
        'store_date' => [
            'required', 'date_format:Y-m-d'
        ],
        'description' => [
            'required', 'string', 'min:3', 'max:100'
        ],
        'amount' => [
            'required'
        ]
    ];


    protected $validationAttributes = [
        'category_id' => 'Nama Kategori',
        'store_date' => 'Tanggal'
    ];


    protected $listeners = [
        'expenseFetched' => 'setCategoryData',
        'expenseCreated' => 'resetCategorySelect'
    ];


    /**
     * Initialize properties data
     *
     * @return void
     */
    public function mount()
    {
        $this->is_new_modal_show = false;
        $this->is_delete_modal_show = false;

        $this->is_edit_mode = false;

        $this->filter_date_range = now()->format('Y-m-d') . ' to ' . now()->addDay()->format('Y-m-d');
        $this->store_date = now()->format('Y-m-d');
    }

    /**
     * Reset page while changing date filter
     *
     * @return void
     */
    public function updatingFilterDateRange()
    {
        $this->resetPage();
    }

    /**
     * Reset page while changing category filter
     *
     * @return void
     */
    public function updatingFilterCategoryId()
    {
        $this->resetPage();
    }

    /**
     * Reset description, amount, expense_id property
     * when user cancel to store/update expense
     *
     * @return void
     */
    public function updatedIsCreateModalShow($value)
    {
        if ($value === false) {
            $this->reset('description', 'amount', 'category_id', 'category_name');
            $this->resetErrorBag();

            if ($this->is_edit_mode === true) {
                $this->reset('expense_id');
            }
        }
    }

    /**
     * Show create expense modal
     *
     * @return void
     */
    public function createExpense()
    {
        $this->is_edit_mode = false;
        $this->is_create_modal_show = true;
    }

    /**
     * Store/update expense into/on database
     *
     * @return void
     */
    public function storeExpense()
    {
        $this->validate();

        try {

            if ($this->is_edit_mode === true) {
                Expense::where('id', $this->expense_id)
                    ->update([
                        'category_id' => $this->category_id,
                        'date' => $this->store_date,
                        'description' => $this->description,
                        'amount' => $this->amount
                    ]);

            } else {
                Expense::create([
                    'category_id' => $this->category_id,
                    'date' => $this->store_date,
                    'description' => $this->description,
                    'amount' => $this->amount
                ]);

            }

            $this->emit('expenseCreated');
            $this->reset('description', 'amount', 'category_id', 'category_name');
            $this->reset('filter_category_id');

            $this->filter_date_range = Carbon::createFromDate($this->store_date)->format('Y-m-d')
                                        . ' to '
                                        . Carbon::createFromDate($this->store_date)->addDay()->format('Y-m-d');

            $this->is_create_modal_show = false;

        } catch (\Throwable $th) {
            report($th);

            session()->flash('create-alert-body', __('Oops, something went wrong'));
        }
    }


    public function setCategoryData(Category $category)
    {
        $this->category_id = $category->id;
        $this->category_name = $category->name;
    }


    public function resetCategorySelect()
    {
        $this->reset('category_id', 'category_name');
    }

    /**
     * Show edit expense modal
     *
     * @param int $expense_id
     * @return void
     */
    public function editExpense($expense_id)
    {
        $expense = Expense::where('id', $expense_id)->with('category')->firstOrFail();

        $this->emit('expenseFetched', $expense->category);

        $this->is_edit_mode = true;
        $this->is_create_modal_show = true;

        $this->expense_id = $expense->id;
        $this->category_id = $expense->category_id;
        $this->category_name = $expense->category->name;
        $this->store_date = $expense->date->format('Y-m-d');
        $this->description = $expense->description;
        $this->amount = $expense->amount;
    }

    /**
     * Show delete modal confirm modal
     *
     * @param int $expense_id
     * @return void
     */
    public function deleteExpense($expense_id)
    {
        $this->is_delete_modal_show = true;
        $this->expense_id = $expense_id;
    }

    /**
     * Reset `expense_id` properties when user cancel to delete expense
     *
     * @return void
     */
    public function updatedIsDeleteModalShow($value)
    {
        if ($value === false) {
            $this->reset('expense_id');
            $this->resetErrorBag();
        }
    }

    /**
     * Delete expense from database
     *
     * @return void
     */
    public function destroyExpense()
    {
        try {
            Expense::destroy($this->expense_id);
            $this->is_delete_modal_show = false;

        } catch (\Throwable $th) {
            report($th);
            session()->flash('delete-alert-body', __('Oops, something went wrong'));

        }
    }

    /**
     * Render to view
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $exDateRange = explode(' to ', $this->filter_date_range);
        $dateStart = $exDateRange[0];
        $dateEnd = end($exDateRange);

        $expenses = Expense::query()
            ->byCategory($this->filter_category_id)
            ->whereBetween('date', [$dateStart, $dateEnd])
            ->with('category')
            ->orderBy('date', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $total_amount = Expense::query()
                        ->byCategory($this->filter_category_id)
                        ->whereBetween('date', [$dateStart, $dateEnd])
                        ->sum('amount');

        $categories = Category::orderBy('name', 'asc')->get();

        return view('livewire.daily-expense-table', compact('expenses', 'total_amount', 'categories'));
    }
}
