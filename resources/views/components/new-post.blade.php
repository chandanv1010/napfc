@props(['data'])
<div class="post-aside mb30">
    <div class="panel-aside lastest">
        <div class="aside-heading">Bài viết mới</div>
        @if(!is_null($data))
        <div class="aside-body">
            @foreach($data as $item)
            @php
                $name = $item->languages->first()->pivot->name;
                $canonical = write_url($item->languages->first()->pivot->canonical);
                $image = $item->image;
                $createdAt = $item->created_at;
            @endphp
            <div class="post-item">
                <a href="{{ $canonical }}" class="image img-cover"><img src="{{ $image }}" alt="{{ $name }}"></a>
                <div class="info">
                    <h3 class="title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a></h3>
                    <div class="created_at">{{ $createdAt }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>