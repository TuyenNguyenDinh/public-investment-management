@php
    use Illuminate\Support\Facades\Route;
@endphp

<ul class="menu-sub">
    @if (isset($menu))
        @foreach ($menu as $submenu)

            {{-- active menu method --}}
            @php
                $activeClass = null;
                $active = $configData["layout"] === 'vertical' ? 'active open':'active';
                $currentRouteName =  Route::currentRouteName();
          
                if ($currentRouteName === $submenu->slug) {
                    $activeClass = 'active';
                }
                elseif ($submenu->children->isNotEmpty()) {
                  if (gettype($submenu->slug) === 'array') {
                    foreach($submenu->slug as $slug){
                      if (str_contains($currentRouteName,$slug) and str_starts_with($currentRouteName, $slug)) {
                          $activeClass = $active;
                      }
                    }
                  }
                  else{
                    if (str_contains($currentRouteName,$submenu->slug) and str_starts_with($currentRouteName, $submenu->slug)) {
                      $activeClass = $active;
                    }
                  }
                }
            @endphp

            <li class="menu-item {{$activeClass}}">
                <a href="{{ isset($submenu->url) ? url($submenu->url) : 'javascript:void(0)' }}"
                   class="{{ $submenu->children->isNotEmpty() ? 'menu-link menu-toggle' : 'menu-link' }}">
                    @if (isset($submenu->icon))
                        <i class="{{ $submenu->icon }}"></i>
                    @endif
                    <div>{{ isset($submenu->name) ? __($submenu->name) : '' }}</div>
                </a>
                {{-- submenu --}}
                @if ($submenu->children->isNotEmpty())
                    @include('layouts.sections.menu.submenu',['menu' => $submenu->children])
                @endif
            </li>
        @endforeach
    @endif
</ul>
