@extends('larrock::admin.main')
@section('title') {{ $package->name }} admin @endsection

@section('content')
    <div class="container-head uk-margin-bottom">
        <div class="uk-grid">
            <div class="uk-width-expand">
                {!! Breadcrumbs::render('admin.'. $package->name .'.create') !!}
            </div>
            <div class="uk-width-auto">
                @if(isset($allowCreate))
                    <a class="uk-button uk-button-primary uk-width-1-1 uk-width-auto@s" href="/admin/{{ $package->name }}/create">Добавить материал</a>
                @endif
            </div>
        </div>
    </div>

    <div class="ibox-content">
        <form id="edit-form-build" class="validate uk-form uk-form-stacked" action="/admin/{{ $package->name }}/{{ $data->id }}" method="POST" novalidate="novalidate">
            <div class="uk-grid">
                <div class="@if(\is_array($package->plugins_backend) && \count($package->plugins_backend) > 0) uk-width-2-3@m @endif uk-width-1-1 uk-form-stacked">
                    <div class="uk-grid uk-grid-small">
                        <!-- Главный таб -->
                    {!! $package->tabs_data['main'] !!}

                    @if(\count($package->tabs_data->except(['main'])) > 0)
                        <!-- Другие табы -->
                            <div class="uk-width-1-1">
                                <ul uk-tab="connect: .main_tabs" class="uk-tab main_tabs_header">
                                    @foreach($package->tabs_data->except(['main']) as $tabs_key => $tabs_value)
                                        <li><a href="">{{ $package->tabs[$tabs_key] }}</a></li>
                                    @endforeach
                                </ul>
                                <ul class="uk-switcher main_tabs uk-margin-remove-top">
                                    @foreach($package->tabs_data->except(['main']) as $tabs_key => $tabs_value)
                                        <li class="uk-grid uk-grid-small">{!! $tabs_value !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                @if(\is_array($package->plugins_backend) && \count($package->plugins_backend) > 0)
                    <div class="uk-width-1-1 uk-width-1-3@m">
                        <ul uk-tab class="uk-tab uk-margin-remove-bottom">
                            @if(\is_array($package->plugins_backend) && array_key_exists('images', $package->plugins_backend))
                                <li><a href="">Фото [<span class="countUploadedImages">{{count($data->getImages)}}</span>]</a></li>
                            @endif
                            @if(\is_array($package->plugins_backend) && array_key_exists('files', $package->plugins_backend))
                                <li><a href="">Файлы [<span class="countUploadedFiles">{{ count($data->getFiles) }}</span>]</a></li>
                            @endif
                        </ul>

                        <ul class="uk-switcher uk-margin plugins uk-panel uk-panel-scrollable">
                            @include('larrock::admin.admin-builder.plugins.images.tab-data-images')
                            @include('larrock::admin.admin-builder.plugins.files.tab-data-files')
                        </ul>
                    </div>
                @endif
            </div>

            <div class="uk-align-right">
                <input name="_method" type="hidden" value="PUT">
                <input name="type_connect" type="hidden" value="{{ $package->name }}">
                <input name="id_connect" type="hidden" value="{{ $data->id }}">
                {{ csrf_field() }}
                <button type="submit" class="uk-button uk-button-primary uk-button-large uk-hidden" form="edit-form-build">Сохранить</button>
            </div>
        </form>
    </div>

    <div class="uk-grid uk-margin-top buttons-save">
        <div class="uk-width-1-2">
            @if(isset($allowDestroy))
                <form class="uk-form" action="/admin/{{ $package->name }}/{{ $data->id }}" method="post" id="test">
                    <input name="_method" type="hidden" value="DELETE">
                    <input name="id_connect" type="hidden" value="{{ $data->id }}">
                    <input name="type_connect" type="hidden" value="{{ $package->name }}">
                    <input name="place" type="hidden" value="material">
                    @if(isset($data->getCategory))
                        @if(isset($data->getCategory->id) && $data->getCategory->id)
                            <input name="category_item" type="hidden" value="{{ $data->getCategory->id }}">
                        @else
                            <input name="category_item" type="hidden" value="{{ $data->getCategory->first()->id }}">
                        @endif
                    @endif
                    {{ csrf_field() }}
                    <button type="submit" class="uk-button uk-button-danger uk-button-large please_conform">Удалить материал</button>
                </form>
            @endif
        </div>
        <div class="uk-width-1-2 uk-text-right">
            @if(isset($allowUpdate))
                <button type="submit" class="uk-button uk-button-primary uk-button-large" form="edit-form-build">Сохранить</button>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/_assets/bower_components/fileapi/dist/FileAPI.min.js"></script>
    <script src="/_assets/bower_components/tinymce/tinymce.min.js"></script>
@endpush