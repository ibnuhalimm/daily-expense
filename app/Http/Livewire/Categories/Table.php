<?php

namespace App\Http\Livewire\Categories;

use App\Actions\Category\UpdateOtherSortNumber;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public $keyword;
    public $isEditMode;
    public $isCreateModalShow;
    public $isDeleteModalShow;

    public $categoryId;
    public $name;
    public $sort_number;


    protected $rules = [
        'name' => [
            'required',
            'max:100'
        ],
        'sort_number' => [
            'integer',
            'min:0'
        ]
    ];


    protected $validationAttributes = [
        'name' => 'Nama Kategori',
        'sort_number' => 'Nomor Urut'
    ];


    public function mount()
    {
        $this->keyword = null;
        $this->isEditMode = false;
        $this->isCreateModalShow = false;
        $this->isDeleteModalShow = false;

        $this->sort_number = 1;
    }


    public function updatingKeyword()
    {
        $this->resetPage();
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }


    public function updatedIsCreateModalShow($value)
    {
        if ($value === false) {
            $this->reset('name');
            $this->reset('sort_number');
            $this->resetErrorBag();

            if ($this->isEditMode === true) {
                $this->reset('categoryId');
            }
        }
    }


    public function updatedIsDeleteModalShow($value)
    {
        if ($value === false) {
            $this->reset('categoryId');
            $this->resetErrorBag();
        }
    }


    public function createCategory()
    {
        $this->isEditMode = false;
        $this->isCreateModalShow = true;
    }


    public function storeCategory()
    {
        $this->validate();

        try {

            if ($this->isEditMode === true) {
                $category = Category::query()
                    ->where('id', $this->categoryId)
                    ->first();

                $category->name = $this->name;
                $category->sort_number = $this->sort_number;
                $category->save();

            } else {
                $category = Category::create([
                    'name' => $this->name,
                    'sort_number' => $this->sort_number
                ]);

            }

            UpdateOtherSortNumber::make()->handle($category, $this->sort_number);

            $this->reset('categoryId', 'name', 'sort_number');
            $this->isCreateModalShow = false;

        } catch (\Throwable $th) {
            report($th);

            session()->flash('create-alert-body', __('Oops, something went wrong'));
        }
    }


    public function editCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $this->isCreateModalShow = true;
        $this->isEditMode = true;
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->sort_number = $category->sort_number;
    }


    public function deleteCategory($categoryId)
    {
        $this->isDeleteModalShow = true;
        $this->categoryId = $categoryId;
    }


    public function destroyCategory()
    {
        try {
            Category::destroy($this->categoryId);
            $this->isDeleteModalShow = false;

        } catch (\Throwable $th) {
            report($th);
            session()->flash('delete-alert-body', __('Oops, something went wrong'));
        }
    }


    public function render()
    {
        $categories = Category::query()
            ->search($this->keyword)
            ->orderBy('sort_number', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view('livewire.categories.table', compact('categories'));
    }
}
