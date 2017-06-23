<!-- Mainly scripts -->
<script src="/_assets/bower_components/uikit/js/uikit.min.js"></script>
<script src="/_assets/bower_components/uikit/js/components/accordion.min.js"></script>
<script src="/_assets/bower_components/uikit/js/components/tooltip.min.js"></script>
<script src="/_assets/bower_components/uikit/js/components/sticky.min.js"></script>
<script src="/_assets/bower_components/uikit/js/core/modal.min.js"></script>
<script src="/_assets/bower_components/noty/js/noty/jquery.noty.js"></script>
<script src="/_assets/_front/_js/front_core.min.js"></script>
@if(isset($validator)) {!! $validator->render() !!} @endif
@stack('scripts')

<a href="#top" title="Переместиться наверх страницы" id="toTop"></a>


@if(App::environment() !== 'local')
    <!-- Yandex.Metrika counter -->

@endif