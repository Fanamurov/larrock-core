@extends('larrock::admin.main')
@section('title') {{ $app->name }} admin @endsection

@section('content')
    <div class="container-head uk-margin-bottom">
        <div class="add-panel uk-margin-bottom uk-text-right">
            <a class="uk-button" href="#modal-help" data-uk-modal="{target:'#modal-help'}"><i class="uk-icon-question"></i></a>
            @if(isset($category))
                <a class="uk-button uk-button-primary" href="/admin/category/{{ $category->id }}/edit">Изменить раздел</a>
                <a href="#add_category" class="uk-button uk-button-primary show-please" data-target="create-category" data-focus="create-category-title">Добавить подраздел</a>
                <a class="uk-button uk-button-primary" href="/admin/{{ $app->name }}/create?category={{ $category->id }}">Добавить материал</a>
            @else
                <a href="#add_category" class="uk-button uk-button-primary show-please" data-target="create-category" data-focus="create-category-title">Добавить раздел</a>
            @endif
        </div>
        <div id="modal-help" class="uk-modal">
            <div class="uk-modal-dialog">
                <a class="uk-modal-close uk-close"></a>
                <p>{{ $app->description }}</p>
            </div>
        </div>
        <div class="uk-clearfix"></div>
        @if(isset($category))
            {!! Breadcrumbs::render('admin.'. $app->name .'.category', $category) !!}
            <div class="uk-clearfix"></div>
            <a class="link-blank" href="{{ $category->full_url }}">{{ $category->full_url }}</a>
        @else
            {!! Breadcrumbs::render('admin.'. $app->name .'.index') !!}
            <div class="uk-clearfix"></div>
            <a class="link-blank" href="/{{ $app->name }}">/{{ $app->name }}</a>
        @endif
    </div>

    @if(isset($categories))
        <p class="uk-h4">Разделы:</p>
        <div class="uk-margin-large-bottom">
            <table class="uk-table uk-table-striped uk-form">
                <thead>
                <tr>
                    <th></th>
                    <th>Название</th>
                    @foreach($app_category->rows as $row)
                        @if($row->in_table_admin || $row->in_table_admin_ajax_editable)
                            <th style="width: 90px" @if($row->name !== 'active') class="uk-hidden-small" @endif>{{ $row->title }}</th>
                        @endif
                    @endforeach
                    @include('larrock::admin.admin-builder.additional-rows-th')
                </tr>
                </thead>
                <tbody class="uk-sortable" data-uk-sortable="{handleClass:'uk-sortable-handle'}">
                @if(isset($category))
                    @include('larrock::admin.category.include-create-easy', array('parent' => $category->id, 'component' => $app->name))
                @else
                    @include('larrock::admin.category.include-create-easy', array('parent' => 0, 'component' => $app->name))
                @endif
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

    @if(isset($data))
        @if(count($data) === 0)
            @if(isset($category->get_child) && $category->get_child === 0)
                <div class="uk-alert uk-alert-warning">Материалов еще нет</div>
            @endif
            @if( !isset($categories))
                <div class="uk-alert uk-alert-warning">Материалов еще нет</div>
            @endif
        @else
            <p class="uk-h4">Материалы:</p>
            <div class="uk-margin-large-bottom">
                <table class="uk-table uk-table-striped uk-form">
                    <thead>
                    <tr>
                        <th class="uk-text-right">
                            @if(isset($category))
                                @php if($category->parent === 0){ $category->parent = NULL; } @endphp
                                <a href="{{ $category->parent or '/admin/'. $app->name }}">
                                    <i class="uk-icon-level-up"></i>..
                                </a>
                            @endif
                        </th>
                        @if(isset($app->rows['title']))
                            <th>{{ $app->rows['title']->title }}</th>
                        @endif
                        @foreach($app->rows as $row)
                            @if($row->in_table_admin || $row->in_table_admin_ajax_editable)
                                <th style="width: 90px" class="@if($row->name !== 'active') uk-hidden-small @endif">{{ $row->title }}</th>
                            @endif
                        @endforeach
                        @include('larrock::admin.admin-builder.additional-rows-th')
                    </tr>
                    </thead>
                    <tbody class="uk-sortable" data-uk-sortable="{handleClass:'uk-sortable-handle'}">
                    @if(count($data) === 0)
                        <div class="uk-alert uk-alert-warning">Материалов еще нет</div>
                    @else
                        @foreach($data as $data_value)
                            <tr>
                                <td width="55">
                                    <a href="/admin/{{ $app->name }}/{{ $data_value->id }}/edit">
                                        @if($app->plugins_backend && array_key_exists('images', $app->plugins_backend) && $image = $data_value->getMedia('images')->sortByDesc('order_column')->first())
                                            <img style="width: 55px" src="{{ $image->getUrl('110x110') }}">
                                        @else
                                            <i class="icon-padding icon-color uk-icon-picture-o" title="Фото не прикреплено"></i>
                                        @endif
                                    </a>
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
                    @endif
                    </tbody>
                </table>
                @if(method_exists($data, 'total'))
                    {!! $data->render() !!}
                @endif
            </div>
        @endif
    @endif

    @if(isset($category->get_child))
        @if(count($category->get_child) === 0)
            <table class="uk-table uk-table-striped">
                @include('larrock::admin.category.include-create-easy', array('parent' => $category->id, 'component' => $app->name))
            </table>
        @else
            <p class="uk-h4">Подразделы:</p>
            <div class="uk-margin-large-bottom">
                <table class="uk-table uk-table-striped uk-form">
                    <thead>
                    <tr>
                        <th>
                            @if(isset($category))
                                @php if($category->parent === 0){ $category->parent = NULL; } @endphp
                                <a href="{{ $category->parent or '/admin/'. $app->name }}">
                                    <i class="uk-icon-level-up"></i>..
                                </a>
                            @endif
                        </th>
                        <th>Название</th>
                        @foreach($app_category->rows as $row)
                            @if($row->in_table_admin || $row->in_table_admin_ajax_editable)
                                <th style="width: 90px" @if($row->name !== 'active') class="uk-hidden-small" @endif>{{ $row->title }}</th>
                            @endif
                        @endforeach
                        @include('larrock::admin.admin-builder.additional-rows-th')
                    </tr>
                    </thead>
                    <tbody class="uk-sortable" data-uk-sortable="{handleClass:'uk-sortable-handle'}">
                    @include('larrock::admin.category.include-create-easy', array('parent' => $category->id, 'component' => $app->name))
                    @include('larrock::admin.category.include-list-categories', array('data' => $category->get_child))
                    </tbody>
                </table>
            </div>
        @endif
    @endif
@endsection