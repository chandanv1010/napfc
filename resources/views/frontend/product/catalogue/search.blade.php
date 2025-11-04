@extends('frontend.homepage.layout')
@section('content')
    <div class="product-catalogue page-wrapper">
        <div class="uk-container uk-container-center mt40">
            <div class="panel-body">
                <h2 class="heading-1 mb20"><span>{{ $seo['meta_title'] }}</span></h2>
                @if(!is_null($products) && count($products))
                <div class="product-list mb30">
                    <div class="uk-grid uk-grid-medium">
                        @foreach($products as $product)
                            <div class="uk-width-small-1-2 uk-width-medium-1-4 mb20">
                                @include('frontend.component.p-item', ['product'  => $product])
                            </div>
                        @endforeach
                    </div>
                    <div class="uk-text-center search-paginate">
                        @if ($products->hasPages())
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @php
                                    $prevPageUrl = ($products->currentPage() > 1) ? str_replace('?page=', '/trang-', $products->previousPageUrl()).config('apps.general.suffix') : null;
                                @endphp
                                @if ($prevPageUrl)
                                    <li class="page-item"><a class="page-link" href="{{ $prevPageUrl }}">Previous</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Previous</span></li>
                                @endif

                                {{-- Pagination Links --}}
                                @foreach ($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                                    @php
                                        // $paginationUrl = str_replace('?page=', '/trang-', $url).config('apps.general.suffix');
                                        // $paginationUrl = ($page == 1) ? str_replace('/trang-'.$page, '', $paginationUrl) : $paginationUrl;
                                        $paginationUrl = $url;
                                    @endphp
                                    <li class="page-item {{ ($page == $products->currentPage()) ? 'active' : '' }}"><a class="page-link" href="{{ $paginationUrl }}">{{ $page }}</a></li>
                                @endforeach

                                {{-- Next Page Link --}}
                                @php
                                    $nextPageUrl = ($products->hasMorePages()) ? str_replace('?page=', '/trang-', $products->nextPageUrl()).config('apps.general.suffix') : null;
                                @endphp
                                @if ($nextPageUrl)
                                    <li class="page-item"><a class="page-link" href="{{ $nextPageUrl }}">Next</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                                @endif
                            </ul>
                        @endif

                    </div>
                </div>
                @else
                    <div class="pt20 pb20">
                        Không có sản phẩm phù hợp....
                    </div>

                @endif
            </div>

        </div>
    </div>

@endsection

