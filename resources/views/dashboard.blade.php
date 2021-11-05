<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-4 md:mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 md:gap-10">
                <x-card-content title="Today Expense" class="mb-6 md:mb-0">
                    <div class="flex items-start justify-center">
                        <span class="font-bold text-xl text-red-500">Rp</span>
                        <span class="font-bold text-3xl ml-2 text-red-500">
                            {{ number_format($today_expense, 0, ',', '.') . ',-' }}
                        </span>
                    </div>
                </x-card-content>
                <x-card-content title="This Month Expense" class="mb-6 md:mb-0">
                    <div class="flex items-start justify-center">
                        <span class="font-bold text-xl text-blue-500">Rp</span>
                        <span class="font-bold text-3xl ml-2 text-blue-500">
                            {{ number_format($this_month_expense, 0, ',', '.') . ',-' }}
                        </span>
                    </div>
                </x-card-content>
                <x-card-content title="Daily Average" class="mb-6 md:mb-0">
                    <div class="flex items-start justify-center">
                        <span class="font-bold text-xl text-green-500">Rp</span>
                        <span class="font-bold text-3xl ml-2 text-green-500">
                            {{ number_format($daily_average, 0, ',', '.') . ',-' }}
                        </span>
                    </div>
                </x-card-content>
            </div>
        </div>


        @livewire('daily-expense-graph')

        @livewire('widget.category-summary')

    </div>
</x-app-layout>