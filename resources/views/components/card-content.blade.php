<div {{ $attributes->merge([
    'class' => 'bg-white overflow-hidden shadow-xl sm:rounded-lg'
]) }}>
    <div class="bg-white border-b border-gray-200">
        <div class="p-6">
            @if (!empty($title))
                <h3 class="text-lg font-medium text-gray-900">
                    {{ $title }}
                </h3>
            @endif
            <div class="mt-6 flex flex-col md:flex-row items-center justify-between">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>