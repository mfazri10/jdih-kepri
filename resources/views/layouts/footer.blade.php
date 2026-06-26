@if(!isset($is_auth) || !$is_auth)
    <footer class="footer">
        <p class="fs-11 text-muted fw-medium text-uppercase mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'DPRD Kepri') }}. All rights reserved.</p>
    </footer>
@endif

<!--! BEGIN: Vendors JS !-->
<script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('assets/vendors/js/daterangepicker.min.js') }}"></script>
<script src="{{ asset('assets/vendors/js/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/vendors/js/circle-progress.min.js') }}"></script>
<!-- Select2 JS -->
<script src="{{ asset('assets/vendors/js/select2.min.js') }}"></script>
<!--! END: Vendors JS !-->

<!--! BEGIN: Apps Init  !-->
<script src="{{ asset('assets/js/common-init.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard-init.min.js') }}"></script>
<!--! END: Apps Init !-->

<!--! BEGIN: Theme Customizer  !-->
<script src="{{ asset('assets/js/theme-customizer-init.min.js') }}"></script>
<!--! END: Theme Customizer !-->

@yield('scripts')
