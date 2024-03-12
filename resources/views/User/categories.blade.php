<div class="categories search-div">
    <h2><img src="data:image/png;base64,{{ $icon['category'] }}" class="icon-filter" width="25"> CATEGORIES</h2>
    <ul>


        @foreach ($parentCategories as $parent_category)
            <li><a href="/parent/category/{{ $parent_category->created_at->timestamp }}/{{ $parent_category->id }}">
                    <img src="data:image/png;base64,{{ $icon['right'] }}" class="icon-filter" width="25">
                    {{ $parent_category->name }}
                </a>
                <span class="count">{{ $parent_category->parentProducts()->where('status', 'Active')->count() }}</span>
                <ul class="sub-cat-ul">
                    @foreach ($subCategories as $sub_category)
                        @if ($parent_category->id === $sub_category->parent_category_id)
                            <li class="sub-cat-li"><a href="/sub/category/{{ $sub_category->created_at->timestamp }}/{{ $sub_category->id }}">
                                    <img src="data:image/png;base64,{{ $icon['right'] }}" class="icon-filter"
                                        width="25">
                                    {{ $sub_category->name }}</a>
                                <span class="count">{{ $sub_category->subProducts()->where('status', 'Active')->count() }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>
