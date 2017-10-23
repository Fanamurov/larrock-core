@extends('larrock::admin.main')
@section('title') {{ $app->name }} admin @endsection

@section('content')
    <div class="container-head uk-margin-bottom">
        <div class="add-panel uk-margin-bottom uk-text-right">
            <a class="uk-button" href="#modal-help" data-uk-modal="{target:'#modal-help'}"><i class="uk-icon-question"></i></a>
            <a class="uk-button uk-button-primary" href="/admin/{{ $app->name }}/create">Добавить материал</a>
        </div>
        <div id="modal-help" class="uk-modal">
            <div class="uk-modal-dialog">
                <a class="uk-modal-close uk-close"></a>
                <p>{{ $app->description }}</p>
            </div>
        </div>
        <div class="uk-clearfix"></div>
        {!! Breadcrumbs::render('admin.'. $app->name .'.index') !!}
        <div class="uk-clearfix"></div>
        <a class="link-blank" href="/{{ $app->name }}/">/{{ $app->name }}/</a>
    </div>

    @if(isset($data))
        <div class="uk-margin-large-bottom">
            <form id="massiveAction" class="uk-alert uk-alert-warning massive_action uk-hidden" method="post" action="/admin/{{ $app->name }}/0">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <p>Выделено: <span>0</span> элементов. <button type="submit" class="uk-button uk-button-danger please_conform">Удалить</button></p>
            </form>
            <table class="uk-table uk-table-striped uk-form">
                <thead>
                <tr>
                    <th></th>
                    @if(isset($app->rows['title']))
                        <th>{{ $app->rows['title']->title }}</th>
                    @endif
                    @foreach($app->rows as $row)
                        @if($row->in_table_admin || $row->in_table_admin_ajax_editable)
                            <th style="width: 90px" @if($row->name !== 'active') class="uk-hidden-small" @endif>{{ $row->title }}</th>
                        @endif
                    @endforeach
                    @include('larrock::admin.admin-builder.additional-rows-th')
                </tr>
                </thead>
                <tbody class="uk-sortable" data-uk-sortable="{handleClass:'uk-sortable-handle'}">
                @foreach($data as $data_value)
                    <tr>
                        <td width="55">
                            <input form="massiveAction" id="id{{ $data_value->id }}" type="checkbox" name="ids[]" value="{{ $data_value->id }}" class="elementId uk-hidden">
                            <div class="actionSelect{{ $data_value->id }} actionSelect" onclick="selectIdItem({{ $data_value->id }})">
                                @if($app->plugins_backend && array_key_exists('images', $app->plugins_backend) && $image = $data_value->getMedia('images')->sortByDesc('order_column')->first())
                                    <img style="width: 55px" src="{{ $image->getUrl('110x110') }}">
                                @else
                                    <i class="icon-padding icon-color uk-icon-picture-o" title="Фото не прикреплено"></i>
                                @endif
                            </div>
                        </td>
                        @if(isset($app->rows['title']))
                            <td>
                                <a class="uk-h4" href="/admin/{{ $app->name }}/{{ $data_value->id }}/edit">{{ $data_value->title }}</a>
                                <br/>
                                <a class="link-to-front" target="_blank" href="{{ $data_value->full_url }}" title="ссылка на элемент на сайте">
                                    {{ str_limit($data_value->full_url, 35, '...') }}
                                </a>
                            </td>
                        @endif
                        @foreach($app->rows as $row)
                            @if($row->in_table_admin_ajax_editable)
                                @if(get_class($row) === 'Larrock\Core\Helpers\FormBuilder\FormCheckbox')
                                    <td class="row-active @if($row->name !== 'active') uk-hidden-small @endif">
                                        <div class="uk-button-group btn-group_switch_ajax" role="group" style="width: 100%">
                                            <button type="button" class="uk-button uk-button-primary uk-button-small @if($data_value->{$row->name} === 0) uk-button-outline @endif"
                                                    data-row_where="id" data-value_where="{{ $data_value->id }}" data-table="{{ $app->table }}"
                                                    data-row="active" data-value="1" style="width: 50%">on</button>
                                            <button type="button" class="uk-button uk-button-danger uk-button-small @if($data_value->{$row->name} === 1) uk-button-outline @endif"
                                                    data-row_where="id" data-value_where="{{ $data_value->id }}" data-table="{{ $app->table }}"
                                                    data-row="active" data-value="0" style="width: 50%">off</button>
                                        </div>
                                    </td>
                                @elseif(get_class($row) === 'Larrock\Core\Helpers\FormBuilder\FormInput')
                                    <td class="uk-hidden-small">
                                        <input type="text" value="{{ $data_value->{$row->name} }}" name="{{ $row->name }}"
                                               class="ajax_edit_row form-control" data-row_where="id" data-value_where="{{ $data_value->id }}"
                                               data-table="{{ $app->table }}">
                                        @if($row->name === 'position')
                                            <i class="uk-sortable-handle uk-icon uk-icon-bars uk-margin-small-right" title="Перенести материал по весу"></i>
                                        @endif
                                    </td>
                                @elseif(get_class($row) === 'Larrock\Core\Helpers\FormBuilder\FormSelect')
                                    <td class="uk-hidden-small">
                                        <select class="ajax_edit_row form-control" data-row_where="id" data-value_where="{{ $data_value->id }}"
                                                data-table="{{ $app->table }}" data-row="{{ $row->name }}">
                                            @foreach($row->options as $option)
                                                <option @if($option === $data_value->{$row->name}) selected @endif value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endif
                            @endif
                            @if($row->in_table_admin)
                                <td class="uk-hidden-small">
                                    {{ $data_value->{$row->name} }}
                                </td>
                            @endif
                        @endforeach
                        @include('larrock::admin.admin-builder.additional-rows-td')
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if(count($data) === 0)
                <div class="uk-alert uk-alert-warning">Данных еще нет</div>
            @endif
            @if(method_exists($data, 'total'))
                {!! $data->render() !!}
            @endif
        </div>
    @endif

    @if(isset($categories))
        <p class="uk-h4">Разделы:</p>
        <div class="uk-margin-large-bottom">
            <table class="uk-table uk-table-striped uk-form">
                <thead>
                <tr>
                    <th></th>
                    <th>Название</th>
                    @include('larrock::admin.admin-builder.additional-rows-th')
                </tr>
                </thead>
                <tbody>
                @include('larrock::admin.category.include-create-easy', array('parent' => $categories->first()->id, 'component' => $app->name))
                @if(count($categories) === 0)
                    <div class="uk-alert uk-alert-warning">Разделов еще нет</div>
                @else
                    @include('larrock::admin.category.include-list-categories', array('data' => $categories))
                @endif
                </tbody>
            </table>
            @if(method_exists($categories, 'total'))
                {!! $categories->render() !!}
            @endif
        </div>
    @endif
@endsection