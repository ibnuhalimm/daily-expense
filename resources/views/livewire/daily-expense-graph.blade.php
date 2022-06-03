<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-card-content>
            <h3 class="text-lg font-medium text-gray-900 -mt-6">
                {{ __('Daily Expense in') }} {{ strftime('%B %Y') }}
            </h3>
            {{-- <div class="w-full flex flex-row items-center justify-end mb-6">
                <div class="w-1/2 lg:w-1/6 mx-1">
                    <x-select wire:model.lazy="filter_month">
                        <option value="" disabled>- Month -</option>
                        @for ($month = 1; $month <= 12; $month++)
                            <option value="{{ ($month < 10) ? '0' . $month : $month }}">
                                {{ strftime('%B', strtotime('2020-' . $month . '-01')) }}
                            </option>
                        @endfor
                    </x-select>
                </div>
                <div class="w-1/3 lg:w-1/6 ml-1">
                    <x-select wire:model.lazy="filter_year">
                        <option value="" disabled>- Year -</option>
                        @for ($year = date('Y'); $year >= (date('Y') - 5); $year--)
                            <option value="{{ $year }}">
                                {{ $year }}
                            </option>
                        @endfor
                    </x-select>
                </div>
            </div> --}}
            <div id="daily-expense-graph" class="w-full h-36 mt-6"></div>
        </x-card-content>
    </div>
</div>

@push('bottom_js')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        const options = {
            chart: {
                type: 'line',
                height: '230px'
            },
            series: [{
                name: 'Total Amount',
                data: {{ json_encode($expense_data) }}
            }],
            xaxis: {
                categories: <?php echo json_encode($expense_day) ?>
            }
        };

        const chart = new ApexCharts(document.querySelector("#daily-expense-graph"), options);
        chart.render();
    </script>
@endpush
