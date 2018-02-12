<!-- Mainly scripts -->
<script src="/_assets/bower_components/uikit/js/uikit.min.js"></script>
<script src="/_assets/bower_components/uikit/js/components/grid.min.js"></script>
<script src="/_assets/bower_components/uikit/js/components/notify.min.js"></script>
<script src="/_assets/bower_components/uikit/js/core/modal.min.js"></script>
<script src="/_assets/bower_components/selectize/dist/js/standalone/selectize.min.js"></script>
<script src="/_assets/bower_components/fancybox/dist/jquery.fancybox.js"></script>
<script src="/vendor/jsvalidation/js/jsvalidation.min.js"></script>
<script src="/_assets/bower_components/jquery.cookie/jquery.cookie.js"></script>
<script src="/_assets/bower_components/pickadate/lib/compressed/picker.js"></script>
<script src="/_assets/bower_components/pickadate/lib/compressed/picker.date.js"></script>
<script src="/_assets/bower_components/jquery.spinner/js/jquery.spinner.js"></script>
<script src="/_assets/_front/_js/frontend.js"></script>
<script src="/_assets/_front/_js/cart.js"></script>
<script src="/_assets/_front/_js/catalog.js"></script>
@if(isset($validator)) {!! $validator->render() !!} @endif
@stack('scripts')

<a href="#top" title="Переместиться наверх страницы" id="toTop"></a>

@if(App::environment() !== 'local')
    <!-- Yandex.Metrika counter -->

@endif