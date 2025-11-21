<form action="{{ route('transaction.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="uk-flex uk-flex-middle">
                @include('backend.dashboard.component.perpage')
            </div>
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    <div class="mr10 filter-select-group">
                        @foreach (['status', 'type'] as $filterField)
                            @php
                                $options = __('transaction.' . $filterField);
                                $selected = request($filterField) ?: old($filterField);
                            @endphp
                            <select name="{{ $filterField }}" class="form-control setupSelect2 ml10">
                                @foreach ($options as $key => $label)
                                    <option value="{{ $key }}" {{ $selected === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        @endforeach
                    </div>
                    @include('backend.dashboard.component.keyword')
                </div>
            </div>
        </div>
    </div>
</form>
