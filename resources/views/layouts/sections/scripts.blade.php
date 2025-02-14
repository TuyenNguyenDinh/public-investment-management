<!-- BEGIN: Vendor JS-->

@vite([
  'resources/assets/vendor/libs/jquery/jquery.js',
  'resources/assets/vendor/libs/popper/popper.js',
  'resources/assets/vendor/js/bootstrap.js',
  'resources/assets/vendor/libs/node-waves/node-waves.js',
  'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
  'resources/assets/vendor/libs/hammer/hammer.js',
  'resources/assets/vendor/libs/typeahead-js/typeahead.js',
  'resources/assets/vendor/js/menu.js',
  'resources/assets/vendor/libs/toastr/toastr.js'
])

@yield('vendor-script')
@include('customs.toasts.toast')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])

<!-- Notification JS-->
@vite(['resources/assets/js/customs/notifications/notification.js'])

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->
<script>
    @php
        $locale = app()->getLocale() ?? 'vn';
        $langs = json_decode(file_get_contents(base_path("lang/$locale.json")));
    @endphp
    const translations = @json($langs);
    const locale = '{{ $locale }}';
</script>

@stack('modals')
@livewireScripts
