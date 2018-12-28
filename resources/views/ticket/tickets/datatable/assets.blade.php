@section('panichd_assets')
    <link rel="StyleSheet" href="{{ asset('vendor/panichd/css/datatables/datatables-dt-' . App\Helpers\Cdn::DataTables . '-r-' . App\Helpers\Cdn::DataTablesResponsive . '.min.css') }}">
@append

@section('footer')
    <script src="{{ asset('vendor/panichd/js/datatables/datatables-dt-' . App\Helpers\Cdn::DataTables . '-r-' . App\Helpers\Cdn::DataTablesResponsive . '.min.js') }}"></script>
@append