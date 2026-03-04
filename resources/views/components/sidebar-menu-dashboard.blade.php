@props(['icon' => null, 'routeName' => null, 'title' => null])

@php
    $isActive = request()->routeIs($routeName);
@endphp

<li>
    <a href="{{ route($routeName) }}"
        class="flex items-center p-2 text-base rounded-lg transition-colors duration-200 group 
        {{ $isActive 
            ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400' 
            : 'text-gray-700 hover:bg-cream-100 hover:text-primary-600 dark:text-gray-200 dark:hover:bg-gray-700' 
        }}">

        @if($icon)
            <div class="flex-shrink-0 w-6 h-6 transition duration-75 
                {{ $isActive 
                    ? 'text-primary-500' 
                    : 'text-gray-500 group-hover:text-primary-500 dark:text-gray-400 dark:group-hover:text-primary-400' 
                }}">
                {!! $icon !!}
            </div>
        @endif

        <span class="ml-3 {{ $isActive ? 'font-bold' : '' }}" sidebar-toggle-item>{{ $title }}</span>
    </a>
</li>