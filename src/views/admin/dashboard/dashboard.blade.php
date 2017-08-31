@extends('larrock::admin.main')
@section('title') Larrock Dashboard @endsection

@section('content')
    <div class="dashboard-page">
        @if(count($coreVersions) > 0)
            <h2 class="uk-margin-bottom-remove">Версии компонентов</h2>
            <p class="uk-margin-top-remove">Всего установлено пакетов: {{ count($coreVersions) }}</p>
            <div class="uk-grid uk-grid-small uk-grid-match" data-uk-grid-match="{target:'.uk-panel'}">
                @foreach($coreVersions as $package)
                    <div class="uk-width-1-1 uk-width-small-1-3 uk-width-medium-1-5 uk-margin-bottom">
                        <div class="uk-panel">
                            <h3 class="uk-panel-title uk-margin-bottom-remove">
                                <a href="https://github.com/Fanamurov/{{ str_replace('fanamurov/', '', $package->name) }}" target="_blank">{{ str_replace('fanamurov/', '', $package->name) }}</a>
                            </h3>
                            <div class="uk-margin-bottom"><strong>{{ $package->version }}</strong> <img src="https://poser.pugx.org/{{ $package->name }}/version" alt="Latest Stable Version"></div>
                            <p><i>{{ $package->description }}</i></p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="uk-alert uk-alert-warning">Статистика версий установленных компонентов не доступна. Файл composer.lock не прочитан.</p>
        @endif
    </div>
@endsection