{{-- @if ($paginator->hasPages())
    <div class="pagination">

        @if ($paginator->currentPage() > 2)
            <a href="{{ $paginator->url(1) }}">1</a>
            @if ($paginator->currentPage() > 3)
                <span class="dots">...</span>
            @endif
        @endif


        @for ($i = max(1, $paginator->currentPage() - 1); $i <= min($paginator->currentPage() + 1, $paginator->lastPage()); $i++)

            @if ($i == $paginator->currentPage())
                <span class="current">{{ $i }}</span>
            @else
                <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
            @endif
        @endfor

  
        @if ($paginator->currentPage() < $paginator->lastPage() - 1)
            @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                <span class="dots">...</span>
            @endif
            <a href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
        @endif
    </div>
    @if ($paginator->total() > 0)
        <div style="color: #4f4b4b; padding: 10px; margin-top: 10px; text-align:center; font-size:1rem;">
            Total Result Found: {{ $paginator->total() }}
        </div>
    @endif
@endif --}}



@if ($paginator->hasPages())
    <div class="flex justify-center items-center mt-8 space-x-4 mb-4">
        {{-- First Page Link --}}
        @if ($paginator->currentPage() > 2)
            <a href="{{ $paginator->url(1) }}" class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out">1</a>
            @if ($paginator->currentPage() > 3)
                <span class="text-gray-500">...</span>
            @endif
        @endif

        {{-- Pagination Elements --}}
        @for ($i = max(1, $paginator->currentPage() - 1); $i <= min($paginator->currentPage() + 1, $paginator->lastPage()); $i++)
            @if ($i == $paginator->currentPage())
                <span class="px-4 py-1 bg-blue-500 text-white rounded-sm">{{ $i }}</span>
            @else
                <a href="{{ $paginator->url($i) }}" class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out">{{ $i }}</a>
            @endif
        @endfor

        {{-- Last Page Link --}}
        @if ($paginator->currentPage() < $paginator->lastPage() - 1)
            @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                <span class="text-gray-500">...</span>
            @endif
            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="text-blue-500 hover:text-blue-700 transition duration-300 ease-in-out">{{ $paginator->lastPage() }}</a>
        @endif
    </div>

    {{-- @if ($paginator->total() > 0)
        <div class="text-gray-500 mt-4 text-center text-sm">Total Results Found: {{ $paginator->total() }}</div>
    @endif --}}
@endif

