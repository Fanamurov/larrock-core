<footer>
    <div class="uk-container uk-container-center body-container">
        <div class="uk-grid">
            <div class="uk-width-1-1 footer-text">
                @if(isset($tekst_v_podvale))
                    {!! $tekst_v_podvale->description !!}
                @endif
            </div>
            <div class="uk-width-1-1 sharing">
                <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
                <script src="//yastatic.net/share2/share.js"></script>
                <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,whatsapp,skype"></div>
            </div>
            <div class="footer-copyright uk-width-1-1 uk-text-right">
                <div>
                    <img class="uk-hidden-small" src="/_assets/_front/_images/icons/ico_mart_white.png" alt="Разработка сайтов в Хабаровске: ДС «Март»">
                    <img class="uk-hidden-medium uk-hidden-large" src="/_assets/_front/_images/icons/ico_mart.png" alt="Разработка сайтов в Хабаровске: ДС «Март»">
                    <a href="http://martds.ru" title="Разработка сайтов в Хабаровске: ДС «Март»">Разработка сайтов в Хабаровске: ДС «Март»</a>
                </div>
            </div>
        </div>
    </div>
</footer>