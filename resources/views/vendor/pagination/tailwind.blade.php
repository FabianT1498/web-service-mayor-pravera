@if ($paginator->hasPages())
    <nav role="Page navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    {!! __('Showing') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <button  disabled class="bg-stone-300 flex justify-center w-8 h-8 items-center transition-colors duration-150 rounded-full shadow-lg">
                            <i class="fas fa-chevron-left text-md text-stone-400"></i>                        
                        </button>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center justify-center w-8 h-8 transition-colors duration-150 rounded-full focus:shadow-xl hover:bg-green-100" aria-label="{{ __('pagination.previous') }}">
                            <i class="fas fa-chevron-left text-md text-green-600"></i>                        
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-bold text-gray-700 bg-white cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <button class="flex items-center justify-center ml-4 w-8 h-8 text-white transition-colors duration-150 bg-green-600 border border-r-0 border-green-600 rounded-full focus:shadow-outline">{{ $page }}</button>
                                @else
                                    <a href="{{ $url }}" class="flex items-center justify-center ml-4 w-8 h-8 text-green-600 transition-colors duration-150 rounded-full focus:shadow-outline hover:bg-green-100" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="ml-4 flex items-center justify-center w-8 h-8 transition-colors duration-150 rounded-full focus:shadow-xl hover:bg-green-100" aria-label="{{ __('pagination.previous') }}">
                            <i class="fas fa-chevron-right text-md text-green-600"></i>                        
                        </a>
                    @else
                        <button class="ml-4 flex items-center justify-center w-8 h-8 transition-colors duration-150 rounded-full focus:shadow-xl hover:bg-green-100">
                            <i class="fas fa-chevron-right text-md text-stone-400"></i>                        
                        </button>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif