@section('panichd_assets')
    @if($editor_enabled)
        <link rel="StyleSheet" href="{{asset('vendor/panichd/css/summernote/summernote-' . App\Helpers\Cdn::Summernote .'-bs4.css')}}">
        @if($codemirror_enabled)
            <link rel="StyleSheet" href="{{asset('vendor/panichd/css/codemirror/codemirror-' . App\Helpers\Cdn::CodeMirror . '.min.css')}}">
            <link rel="StyleSheet" href="{{asset('vendor/panichd/css/codemirror/codemirror-' . App\Helpers\Cdn::CodeMirror . '-' . $codemirror_theme . '.min.css')}}">
        @endif
    @endif
@append

@section('footer')
    @if($codemirror_enabled)
        <script src="{{asset('vendor/panichd/js/codemirror/codemirror-' . App\Helpers\Cdn::CodeMirror . '.min.js')}}"></script>
    @endif

    <script src="{{ asset('vendor/panichd/js/summernote/summernote-' . App\Helpers\Cdn::Summernote .'-bs4.min.js') }}"></script>
    @if($editor_locale)
        <script src="{{ asset('vendor/panichd/js/summernote/lang/summernote-'.$editor_locale.'.js') }}"></script>
    @endif
@append