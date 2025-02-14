@php
   use Illuminate\Support\Facades\Route;
   $configData = Helper::appClasses();
@endphp
   <!-- Horizontal Menu -->
<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal  menu bg-menu-theme flex-grow-0">
   <div class="{{$containerNav}} d-flex h-100">
      <ul class="menu-inner pb-2 pb-xl-0">
         @foreach ($menuData as $menu)

            {{-- active menu method --}}
            @php
               $activeClass = null;
               $currentRouteName =  Route::currentRouteName();
       
               if ($currentRouteName === $menu->slug) {
                   $activeClass = 'active';
               }
               elseif ($menu->children->isNotEmpty()) {
                 if (gettype($menu->slug) === 'array') {
                   foreach($menu->slug as $slug){
                     if (str_contains($currentRouteName,$slug) and str_starts_with($currentRouteName, $slug)) {
                       $activeClass = 'active';
                     }
                   }
                 }
                 else{
                   if (str_contains($currentRouteName,$menu->slug) and str_starts_with($currentRouteName, $menu->slug)) {
                     $activeClass = 'active';
                   }
                 }
       
               }
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
                  @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
               @endif
            </li>
         @endforeach
      </ul>
   </div>
</aside>
<!--/ Horizontal Menu -->
