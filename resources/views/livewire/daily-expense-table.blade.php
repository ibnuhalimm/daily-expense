<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="bg-white border-b border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">
                        Daily Expense Data
                    </h3>
                    <div class="mt-6 flex flex-col md:flex-row items-center justify-between">
                        <div class="w-full md:w-2/3 mb-4 sm:mb-0">
                            <x-jet-button wire:click="createExpense">
                                New Expense
                            </x-jet-button>
                        </div>
                        <div class="w-full md:w-1/3">
                            <div class="flex items-center justify-between md:justify-end">
                                <div class="w-1/4 mr-1">
                                    <x-select wire:model.lazy="filter_day">
                                        <option value="" disabled>- Day -</option>
                                        @for ($day = 1; $day <= 31; $day++)
                                            <option value="{{ ($day < 10) ? '0' . $day : $day }}">
                                                {{ $day }}
                                            </option>
                                        @endfor
                                    </x-select>
                                </div>
                                <div class="w-1/3 mx-1">
                                    <x-select wire:model.lazy="filter_month">
                                        <option value="" disabled>- Month -</option>
                                        @for ($month = 1; $month <= 12; $month++)
                                            <option value="{{ ($month < 10) ? '0' . $month : $month }}">
                                                {{ strftime('%B', strtotime('2020-' . $month . '-01')) }}
                                            </option>
                                        @endfor
                                    </x-select>
                                </div>
                                <div class="w-1/3 ml-1">
                                    <x-select wire:model.lazy="filter_year">
                                        <option value="" disabled>- Year -</option>
                                        @for ($year = date('Y'); $year >= (date('Y') - 5); $year--)
                                            <option value="{{ $year }}">
                                                {{ $year }}
                                            </option>
                                        @endfor
                                    </x-select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-0">
                    <x-primary-table>
                        <thead>
                            <x-tr-head>
                                <x-th class="w-20 pl-6">#</x-th>
                                <x-th>Description</x-th>
                                <x-th>Amount</x-th>
                                <x-th class="w-32">&nbsp;</x-th>
                            </x-tr-head>
                        </thead>
                        <tbody>
                            @if ($expenses->isEmpty())
                                <x-tr-body class="hover:bg-gray-100">
                                    <td class="py-3 px-6" colspan="4">
                                        No data available
                                    </td>
                                </x-tr-body>
                            @else
                                @foreach ($expenses as $idx => $expense)
                                    <x-tr-body>
                                        <td class="py-2 px-1 pl-6">
                                            {{ $idx + $expenses->firstItem() }}
                                        </td>
                                        <td class="py-2 px-1">
                                            {{ $expense->description }}
                                        </td>
                                        <td class="py-2 px-1">Rp {{ number_format($expense->amount, 0, ',', '.') }},-</td>
                                        <td class="py-2 px-1 pr-6">
                                            <x-table-button type="button" wire:click="editExpense({{ $expense->id }})">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </x-table-button>
                                            <x-table-button type="button" wire:click="deleteExpense({{ $expense->id }})">
                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </x-table-button>
                                        </td>
                                    </x-tr-body>
                                @endforeach
                            @endif
                        </tbody>
                    </x-primary-table>

                    <div class="px-6 my-3">
                        {!! $expenses->links() !!}
                    </div>

                </div>
                <div class="text-center py-4 px-6">
                    Total Amount : <span class="text-red-600">Rp {{ number_format($total_amount, 0, ',', '.') }},-</span>
                </div>
            </div>
        </div>
    </div>


    <x-jet-dialog-modal wire:model="is_create_modal_show" class="md:w-1/3 modal-oi">
        <x-slot name="title">
            @if ($is_edit_mode === true) Edit Expense @else New Expense @endif
        </x-slot>

        <x-slot name="content">

            @if (session('create-alert-body'))
                <div class="font-medium text-sm text-red-600 mb-4">
                    {{ session('create-alert-body') }}
                </div>
            @endif

            <form action="#" method="post" wire:submit.prevent="storeExpense">
            <div class="col-span-6 sm:col-span-4 mb-6">
                <x-jet-label for="date" value="{{ __('Date') }}" />
                <div class="w-full md:w-2/3 flex items-center justify-between mt-1">
                    <div class="w-1/4 mr-1">
                        <x-select wire:model.defer="day">
                            <option value="" disabled>- Day -</option>
                            @for ($day = 1; $day <= 31; $day++)
                                <option value="{{ ($day < 10) ? '0' . $day : $day }}">
                                    {{ $day }}
                                </option>
                            @endfor
                        </x-select>
                    </div>
                    <div class="w-1/3 mx-1">
                        <x-select wire:model.defer="month">
                            <option value="" disabled>- Month -</option>
                            @for ($month = 1; $month <= 12; $month++)
                                <option value="{{ ($month < 10) ? '0' . $month : $month }}">
                                    {{ strftime('%B', strtotime('2020-' . $month . '-01')) }}
                                </option>
                            @endfor
                        </x-select>
                    </div>
                    <div class="w-1/3 ml-1">
                        <x-select wire:model.defer="year">
                            <option value="" disabled>- Year -</option>
                            @for ($year = date('Y'); $year >= (date('Y') - 1); $year--)
                                <option value="{{ $year }}">
                                    {{ $year }}
                                </option>
                            @endfor
                        </x-select>
                    </div>
                </div>
                <x-jet-input-error for="day" class="mt-2" />
                <x-jet-input-error for="month" class="mt-2" />
                <x-jet-input-error for="year" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mb-6">
                <x-jet-label for="description" value="{{ __('Description') }}" />
                <x-jet-input id="description" type="text" class="mt-1 block w-full" wire:model.defer="description" autocomplete="description" />
                <x-jet-input-error for="description" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mb-6">
                <x-jet-label for="amount" value="{{ __('Amount') }}" />
                <x-jet-input id="amount" type="number" class="mt-1 block w-full" wire:model.defer="amount" autocomplete="amount" />
                <x-jet-input-error for="amount" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button type="button" wire:click="$toggle('is_create_modal_show')" wire:loading.attr="disabled" wire:target="storeExpense">
                Cancel
            </x-jet-secondary-button>

            <x-jet-button type="submit" class="ml-2" wire:loading.attr="disabled" wire:target="storeExpense">
                <span wire:loading.remove wire:target="storeExpense">
                    Save
                </span>
                <span wire:loading wire:target="storeExpense">
                    Saving...
                </span>
            </x-jet-button>
            </form>
        </x-slot>
    </x-jet-dialog-modal>


    <x-jet-confirmation-modal wire:model="is_delete_modal_show">
        <x-slot name="title">
            Delete Expense
        </x-slot>

        @if (session('delete-alert-body'))
            <div class="font-medium text-sm text-red-600 mb-4">
                {{ session('delete-alert-body') }}
            </div>
        @endif

        <x-slot name="content">
            Are you sure you want to delete this expense?
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('is_delete_modal_show')" wire:loading.attr="disabled" wire:target="destroyExpense">
                No, Close
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="destroyExpense" wire:loading.attr="disabled" wire:target="destroyExpense">
                <span wire:loading.remove wire:target="destroyExpense">
                    Yes, Delete
                </span>
                <span wire:loading wire:target="destroyExpense">
                    Deleting...
                </span>
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

</div>
