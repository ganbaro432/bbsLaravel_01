@if ($paginator->hasPages())
<nav aria-label="Page navigation">
    <ul class="pagination relative z-0 inline-flex shadow-sm rounded-md" role="navigation">
        {{-- First Page Link --}}
            <!-- 最初のページへのリンク -->
            <a class="page-link" href="{{ $paginator->url(1) }}"><li class="page-item {{ $paginator->onFirstPage() ? ' disabled relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md leading-5' : ' relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150' }}">
                &laquo;
            </li></a>

        



        {{-- Pagination Elemnts --}}
        {{-- Array Of Links --}}
        {{-- 定数よりもページ数が多い時 --}}
        @if ($paginator->lastPage() > config('const.PAGINATE.LINK_NUM'))

        {{-- 現在ページが表示するリンクの中心位置よりも左の時 --}}
        @if ($paginator->currentPage() <= floor(config('const.PAGINATE.LINK_NUM') / 2))
            <?php $start_page = 1; //最初のページ ?> 
            <?php $end_page = config('const.PAGINATE.LINK_NUM'); ?>

        {{-- 現在ページが表示するリンクの中心位置よりも右の時 --}}
        @elseif ($paginator->currentPage() > $paginator->lastPage() - floor(config('const.PAGINATE.LINK_NUM') / 2))
            <?php $start_page = $paginator->lastPage() - (config('const.PAGINATE.LINK_NUM') - 1); ?>
            <?php $end_page = $paginator->lastPage(); ?>

        {{-- 現在ページが表示するリンクの中心位置の時 --}}
        @else
            <?php $start_page = $paginator->currentPage() - (floor((config('const.PAGINATE.LINK_NUM') % 2 == 0 ? config('const.PAGINATE.LINK_NUM') - 1 : config('const.PAGINATE.LINK_NUM'))  / 2)); ?>
            <?php $end_page = $paginator->currentPage() + floor(config('const.PAGINATE.LINK_NUM') / 2); ?>
        @endif

        {{-- 定数よりもページ数が少ない時 --}}
        @else
            <?php $start_page = 1; ?>
            <?php $end_page = $paginator->lastPage(); ?>
        @endif

        {{-- 処理部分 --}}
        @for ($i = $start_page; $i <= $end_page; $i++)
            @if ($i == $paginator->currentPage())
                <li class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-gray-500 border border-gray-300 cursor-default leading-5 page-item active"><span class="page-link">{{ $i }}</span></li>
            @else
                <a class="page-link" href="{{ $paginator->url($i) }}">
                <li class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 page-item">{{ $i }}</li></a>
            @endif
        @endfor



     

        {{-- Last Page Link --}}
        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}"><li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? ' disabled relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5' : ' relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150' }}">
            &raquo;
        </li></a>

    </ul>
</nav>
@endif