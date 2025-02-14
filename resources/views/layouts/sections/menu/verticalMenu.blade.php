@php
    use Illuminate\Support\Facades\Route;
    $configData = Helper::appClasses();
    $user = auth()->user();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    @if(!isset($navbarFull))
        <div class="app-brand demo">
            <a href="{{url('/')}}" class="app-brand-link">
                <span class="app-brand-logo demo">
                     @if(empty($configs['logo']))
                        @include('_partials.macros',["height"=>20])
                    @else
                        <img class="w-100" src="{{ asset($configs['logo']) }}" alt="Logo">
                    @endif
                </span>
                <span
                    class="app-brand-text demo menu-text fw-bold"
                    style="white-space: nowrap; width: 150px; overflow: hidden;text-overflow: ellipsis;">
                    {{empty($configs['app_name']) ? config('variables.templateName') : $configs['app_name']}}
                </span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
                <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
            </a>
        </div>
    @endif

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @foreach ($menuData as $menu)

            {{-- adding active and open class if child is active --}}
            @if (!empty($menu->group_menu_flag))
                <li class="menu-header small">
                    <span class="menu-header-text">{{ __($menu->name) }}</span>
                </li>
            @else
                {{-- active menu method --}}
                @php
                    $activeClass = null;
                    $currentRouteName = Route::currentRouteName();
              
                    if ($menu->children->isNotEmpty()) {
                      if (gettype($menu->slug) === 'array') {
                        foreach($menu->slug as $slug){
                          if (str_contains($currentRouteName,$slug) and str_starts_with($currentRouteName, $slug)) {
                            $activeClass = 'active open';
                          }
                        }
                      }
                      else{
                        if (str_contains($currentRouteName,$menu->slug) and str_starts_with($currentRouteName, $menu->slug)) {
                          $activeClass = 'active open';
                        }
                      }
                    } elseif ($currentRouteName === $menu->slug) {
                      $activeClass = 'active';
                    }

                    $name = $menu->name;
                @endphp
                {{-- main menu --}}
                <li class="menu-item {{$activeClass}}">
                    <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                       class="{{ $menu->children->isNotEmpty() ? 'menu-link menu-toggle' : 'menu-link' }}">
                        @isset($menu->icon)
                            <i class="{{ $menu->icon }}"></i>
                        @endisset
                        <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
                    </a>
                    {{-- submenu --}}
                    @if($menu->children->isNotEmpty())
                        @include('layouts.sections.menu.submenu',['menu' => $menu->children])
                    @endif
                </li>
            @endif
        @endforeach
    </ul>

</aside>
