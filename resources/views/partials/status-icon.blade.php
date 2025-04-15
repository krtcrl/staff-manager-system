@props(['status'])

@php
    $statuses = [
        'approved' => [
            'label' => 'Approved',
            'color' => 'green',
            'icon' => 'check-circle',
            'description' => 'This request has been approved and processed.'
        ],
        'pending' => [
            'label' => 'Pending',
            'color' => 'amber',
            'icon' => 'clock',
            'description' => 'This request is pending approval. Please wait.'
        ],
        'rejected' => [
            'label' => 'Rejected',
            'color' => 'red',
            'icon' => 'x-circle',
            'description' => 'This request was rejected. Contact support for details.'
        ],
    ];

    $data = $statuses[$status] ?? [
        'label' => 'Unknown',
        'color' => 'gray',
        'icon' => 'question-mark-circle',
        'description' => 'Status is not recognized.'
    ];
@endphp

<div x-data="{ showTooltip: false }" class="relative inline-block">
    <span
        @mouseenter="showTooltip = true"
        @mouseleave="showTooltip = false"
        class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full text-sm font-medium transition-all duration-150
               bg-{{ $data['color'] }}-100 text-{{ $data['color'] }}-800
               dark:bg-{{ $data['color'] }}-900 dark:text-{{ $data['color'] }}-100
               shadow-sm hover:shadow-md cursor-pointer"
    >
        <svg xmlns="http://www.w3.org/2000/svg"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             class="w-4 h-4"
             aria-hidden="true">
            @switch($data['icon'])
                @case('check-circle')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2l4 -4M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/>
                    @break

                @case('clock')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6l4 2m4-2a8 8 0 1 1-8-8a8 8 0 0 1 8 8z"/>
                    @break

                @case('x-circle')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 10l4 4m0-4l-4 4M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z"/>
                    @break

                @default
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 18h.01M12 14v-4m0 0a4 4 0 1 1 4 4H8a4 4 0 0 1 4-4z"/>
            @endswitch
        </svg>
        <span>{{ $data['label'] }}</span>
    </span>

    <!-- Tooltip -->
    <div x-show="showTooltip" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 z-10 w-48 px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg"
         style="display: none;">
        {{ $data['description'] }}
    </div>
</div>
