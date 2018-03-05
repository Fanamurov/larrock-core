@extends('larrock::admin.main')
@section('title') Larrock Dashboard @endsection

@section('content')
    <div class="dashboard-page">
        @if(count($toDashboard) > 0)
            <div class="uk-grid uk-grid-medium" uk-grid>
                @foreach($toDashboard as $item)
                    {!! $item !!}
                @endforeach
            </div>
        @endif

        @if(count($coreVersions) > 0)
            <div class="uk-margin-large-top">
                <h4 class="uk-link" onclick="$('.packages-list, .pack').slideToggle()">Установлено компонентов:
                    {{ count($coreVersions) }}/{{ count($full_packages_list)+count($coreVersions) }}
                    <span class="pack">(свернуто)</span></h4>
                <div class="packages-list uk-child-width-1-4@m uk-grid-match uk-grid" uk-grid style="display: none">
                    @foreach($coreVersions as $package)
                        <div class="uk-margin-bottom">
                            <div class="uk-card uk-card-small uk-card-default">
                                <div class="uk-card-body">
                                    <h4>
                                        <a href="https://github.com/Fanamurov/{{ str_replace('fanamurov/', '', $package->name) }}" target="_blank">
                                            {{ str_replace('fanamurov/', '', $package->name) }}</a>
                                    </h4>
                                    <p>{{ $package->description }}</p>
                                    <span class="uk-label uk-text-lowercase">{{ $package->version }}</span>
                                    <img src="https://poser.pugx.org/{{ $package->name }}/version" alt="Latest Stable Version">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @foreach($full_packages_list as $key => $item)
                        <div class="uk-margin-bottom">
                            <div class="uk-card uk-card-small uk-card-secondary">
                                <div class="uk-card-body">
                                    <div class="uk-card-badge uk-label">
                                        <a href="https://github.com/Fanamurov/{{ str_replace('fanamurov/', '', $key) }}" target="_blank">
                                            {{ str_replace('fanamurov/', '', $key) }}</a></div>
                                    <h4 class="uk-card-title">Не установлен</h4>
                                    <p>{{ $item }} <img src="https://poser.pugx.org/{{ $key }}/version" alt="Latest Stable Version"></p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p uk-alert>Статистика версий установленных компонентов не доступна. Файл composer.lock не прочитан.</p>
        @endif
    </div>
@endsection