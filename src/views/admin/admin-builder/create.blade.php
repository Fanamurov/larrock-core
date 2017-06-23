@extends('larrock::admin.main')
@section('title') {{ $app->name }} admin @endsection

@section('content')
    <div class="container-head uk-margin-bottom">
        {!! Breadcrumbs::render('admin.'. $app->name .'.create') !!}
    </div>

    <ul class="uk-tab" data-uk-tab="{connect:'#tab-content, #tab-content-plugins'}">
        @foreach($app->tabs as $tabs_key => $tabs_value)
            <li class="@if($loop->first) uk-active @endif">
                <a href="">{{ $tabs_value }}</a>
            </li>
            @if($loop->first && $app->plugins_backend)
                @if(array_key_exists('images', $app->plugins_backend))
                    @include('larrock::admin.admin-builder.plugins.images.tab-title')
                @endif
                @if(array_key_exists('images', $app->plugins_backend))
                    @include('larrock::admin.admin-builder.plugins.files.tab-title')
                @endif
            @endif
        @endforeach
    </ul>

    <div class="ibox-content">
        <form class="validate uk-form uk-form-stacked" action="/admin/{{ $app->name }}" method="POST" novalidate="novalidate">
            <input name="_method" type="hidden" value="POST">
            <input name="type_connect" type="hidden" value="{{ $app->name }}">
            <input name="id_connect" type="hidden" value="{{ DB::table($app->table)->max('id') +1 }}">

            <ul id="tab-content" class="uk-switcher uk-margin">
                @foreach($app->tabs_data as $tabs_key => $tabs_value)
                    <li id="tab{{ $tabs_key }}">
                        <div class="uk-grid">{!! $tabs_value !!}</div>
                    </li>
                    @if($loop->first && $app->plugins_backend) <li></li><li></li> @endif
                @endforeach
            </ul>

            <div class="uk-align-right">
                {{ csrf_field() }}
                <button type="submit" class="uk-button uk-button-primary uk-button-large">Сохранить</button>
            </div>
            <div class="uk-clearfix"></div>
        </form>

        @if($app->plugins_backend)
            <ul id="tab-content-plugins" class="uk-switcher uk-margin">
                @foreach($app->tabs as $tabs_key => $tabs_value) <li></li>
                @if($loop->first && $app->plugins_backend)
                    @if(array_key_exists('images', $app->plugins_backend))
                        @include('larrock::admin.admin-builder.plugins.images.tab-data')
                    @endif
                    @if(array_key_exists('images', $app->plugins_backend))
                        @include('larrock::admin.admin-builder.plugins.files.tab-data')
                    @endif
                @endif
                @endforeach
            </ul>
        @endif
    </div>
@endsection