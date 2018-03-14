@if(isset($errors))
    @foreach($errors->all() as $error)
        <div class="uk-alert uk-alert-danger alert-dismissable">
            <a href="" class="uk-alert-close uk-close"></a>
            {!! $error !!}
        </div>
    @endforeach
@endif
@if(Session::has('message') && is_array(Session::get('message')))
    @foreach(Session::get('message') as $type => $messages)
        @foreach($messages as $message)
            <div class="uk-alert uk-alert-{{ $type }} alert-dismissable">
                @if($type === 'danger') <i class="uk-icon-bug"></i> @else <i class="uk-icon-check"></i> @endif
                    {{ $message }} <a href="" class="uk-alert-close uk-close uk-float-right"></a>
            </div>
        @endforeach
    @endforeach
    @php(\Illuminate\Support\Facades\Session::forget('message'))
@endif