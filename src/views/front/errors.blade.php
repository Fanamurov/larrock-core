@if(isset($errors))
    @foreach($errors->all() as $error)
        <div class="uk-alert uk-alert-danger alert-dismissable">
            <a href="" class="uk-alert-close uk-close"></a>
            {!! $error !!}
        </div>
    @endforeach
@endif
@foreach (Alert::getMessages() as $type => $messages)
    @foreach ($messages as $message)
        @php if($type === 'error'){ $type = 'danger';} @endphp
        <div class="uk-alert uk-alert-{{ $type }} alert-dismissable">
            <a href="" class="uk-alert-close uk-close"></a>
            {!! $message !!}
        </div>
    @endforeach
@endforeach