@props(['posts'])

@if(isset($posts) && count($posts))
    <div class="uk-grid uk-grid-medium">
        <div class="uk-width-1-1 uk-width-small-1-1 uk-width-medium-1-2">
            @foreach($posts as $item)
                @if($loop->first)
                    <x-article-overlay-card 
                        :class="'overlay'" 
                        :name="$item->languages[0]->name"
                        :canonical="write_url($item->languages[0]->canonical)"
                        description="{!! $item->languages[0]->description !!}"
                        :image="$item->image"
                        :created="$item->created_at"
                    />
                @endif
            @endforeach
        </div> 
        <div class="uk-width-1-1 uk-width-small-1-1 uk-width-medium-1-2">
            <div class="list-posts">
                @foreach($posts as $keyItem => $item)
                @if($keyItem === 0) @continue @endif
                @if($keyItem > 3) @break @endif
                
                <x-article-left-image-card 
                    :class="'article-custom mb25'"
                    :name="$item->languages[0]->name"
                    description="{!! $item->languages[0]->description !!}"
                    :created="$item->created_at"
                    :image="$item->image"
                    :canonical="write_url($item->languages[0]->canonical)"
                />
                @endforeach
            </div>
        </div>
    </div>
@endif