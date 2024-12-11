
<ul>
    @foreach ($children as $child)
        <li>
            {{ $child['name'] }}
            @if (!empty($child['children']))
                @include('partials.category-children', ['children' => $child['children']])
            @endif
        </li>
    @endforeach
</ul>