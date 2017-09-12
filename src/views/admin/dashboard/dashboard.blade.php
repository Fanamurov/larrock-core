@extends('larrock::admin.main')
@section('title') Larrock Dashboard @endsection

@section('content')
    <div class="dashboard-page">
        <h1>Панель управления LarrockCMS</h1>

        @if(count($toDashboard) > 0)
            <div class="dashboard-components uk-margin-bottom uk-margin-large-top" data-uk-grid="{ gutter: 10 }">
                @foreach($toDashboard as $item)
                    {!! $item !!}
                @endforeach
            </div>
            <hr/>
        @endif

        @if(count($coreVersions) > 0)
            <div class="coreVersions uk-margin-large-top">
                <h2 class="uk-margin-bottom-remove uk-link" data-uk-toggle="{target:'#packages-list'}">Версии компонентов</h2>
                <p class="uk-margin-top-remove">Всего установлено пакетов: {{ count($coreVersions) }}/{{ count($full_packages_list)+count($coreVersions) }}</p>
                <div id="packages-list" class="uk-hidden uk-grid uk-grid-small uk-grid-match packages-list" data-uk-grid-match="{target:'.uk-panel'}">
                    @foreach($coreVersions as $package)
                        <div class="uk-width-1-1 uk-width-small-1-3 uk-width-medium-1-5 uk-margin-bottom">
                            <div class="uk-panel uk-alert data-uk-tooltip" title="{{ $package->description }}">
                                <h3 class="uk-panel-title uk-margin-bottom-remove">
                                    <i class="uk-icon-plug"></i><a href="https://github.com/Fanamurov/{{ str_replace('fanamurov/', '', $package->name) }}" target="_blank">{{ str_replace('fanamurov/', '', $package->name) }}</a>
                                </h3>
                                <div><img src="https://poser.pugx.org/{{ $package->name }}/version" alt="Latest Stable Version"> {{ $package->version }}</div>
                                <p><i>{{ $package->description }}</i></p>
                            </div>
                        </div>
                    @endforeach
                    @foreach($full_packages_list as $key => $item)
                        <div class="uk-width-1-1 uk-width-small-1-3 uk-width-medium-1-5 uk-margin-bottom">
                            <div class="uk-panel">
                                <h3 class="uk-panel-title uk-margin-bottom-remove">
                                    <a href="https://github.com/Fanamurov/{{ str_replace('fanamurov/', '', $key) }}" target="_blank">{{ str_replace('fanamurov/', '', $key) }}</a>
                                </h3>
                                <div class="uk-margin-bottom">Не установлен <img src="https://poser.pugx.org/{{ $key }}/version" alt="Latest Stable Version"></div>
                                <p><i>{{ $item }}</i></p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="uk-alert uk-alert-warning">Статистика версий установленных компонентов не доступна. Файл composer.lock не прочитан.</p>
        @endif
    </div>
@endsection