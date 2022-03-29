@if ($paginator->hasPages())
    <nav role="Page navigation" aria-label="{{ __('Pagination Navigation') }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    {!! __('Mostrando desde el') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('hasta el') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('de') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('resultados') !!}
                </p>
            </div>

            <div>
                <span id="pages_links_container" class="relative z-0 inline-flex">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <button  disabled class="bg-stone-300 flex justify-center w-8 h-8 items-center transition-colors duration-150 rounded-full shadow-lg">
                            <i class="fas fa-chevron-left text-md text-stone-400"></i>                        
                        </button>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center justify-center w-8 h-8 transition-colors duration-150 rounded-full focus:shadow-xl hover:bg-gray-300" aria-label="{{ __('pagination.previous') }}">
                            <i class="fas fa-chevron-left text-md text-gray-800"></i>                        
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
                                    <button class="flex items-center justify-center ml-4 w-8 h-8 text-white transition-colors duration-150 bg-gray-800 border border-r-0 border-gray-800 rounded-full focus:shadow-outline">{{ $page }}</button>
                                @else
                                    <a href="{{ $url }}" class="flex items-center justify-center ml-4 w-8 h-8 text-gray-800 transition-colors duration-150 rounded-full focus:shadow-outline hover:bg-gray-300" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="ml-4 flex items-center justify-center w-8 h-8 transition-colors duration-150 rounded-full focus:shadow-xl hover:bg-gray-300" aria-label="{{ __('pagination.previous') }}">
                            <i class="fas fa-chevron-right text-md text-gray-800"></i>                        
                        </a>
                    @else
                        <button  disabled class="ml-4 bg-stone-300 flex justify-center w-8 h-8 items-center transition-colors duration-150 rounded-full shadow-lg">
                            <i class="fas fa-chevron-right text-md text-stone-400"></i>                        
                        </button>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif