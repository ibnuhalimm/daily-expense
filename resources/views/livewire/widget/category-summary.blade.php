<div class="my-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-card-content>
            <h3 class="text-lg font-medium text-gray-900 -mt-6">
                Monthly Expense Category
            </h3>
            <div class="mt-6 w-full">
                <div>
                    <div class="flex flex-row items-center gap-4">
                        <div class="w-40">
                            <x-select wire:model.lazy="month">
                                <option value="" disabled>- Month -</option>
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ ($month < 10) ? '0' . $month : $month }}">
                                        {{ strftime('%B', strtotime('2020-' . $month . '-01')) }}
                                    </option>
                                @endfor
                            </x-select>
                        </div>
                        <div class="w-24">
                            <x-select wire:model.lazy="year">
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
                <div class="mt-6">
                    @foreach ($categories as $category)
                        @php
                            $percentage_value = round($category->amount_sum / $sumOfTotalAmount * 100, 2);
                        @endphp
                        <div class="mb-5 py-2 border border-dashed border-t-0 border-r-0 border-l-0 border-gray-400">
                            <div class="flex flex-row items-center justify-between">
                                <div>
                                    {{ $category->name }}
                                </div>
                                <div class="text-right text-sm">
                                    IDR {{ number_format($category->amount_sum, 2, ',', '.') }}
                                </div>
                            </div>
                            <div class="relative">
                                <div class="px-2 h-4 rounded-full bg-green-400" role="progressbar"
                                    style="width: {{ (int) $percentage_value }}%"
                                    aria-valuenow="{{ (int) $percentage_value }}" aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                                <div class="absolute -top-1 right-0 text-sm font-bold">
                                    <span class="relative top-[.1rem]">
                                        {{ $percentage_value }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-card-content>
    </div>
</div>