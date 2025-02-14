<option value="{{ $organization->id }}"
    {{ (isset($first_selected) && $first_selected && session('organization_id') == $organization->id)
        || (isset($is_check) && in_array($organization->id, $is_check)) ? 'selected' : '' }}>
    {{ $prefix }} {{ $organization->name }}
</option>

@if ($organization->children->isNotEmpty())
    @foreach ($organization->children as $child)
        @include('content.apps.partials.organization_option', [
            'organization' => $child,
            'prefix' => $prefix . '-',
            'is_check' => $is_check ?? [],
        ])
    @endforeach
@endif
