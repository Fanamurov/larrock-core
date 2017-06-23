@extends('larrock::admin.clean')
@section('title', 'Сброс пароля')

<!-- Main Content -->
@section('content')
    <div class="uk-container-center auth-container">
        <div class="uk-grid background-blue">
            <div class="uk-width-1-1 uk-width-medium-1-2 logo-row uk-hidden-small">
                <img src="/_assets/_admin/_images/logo-rock-256.png" class="max-width text-center">
            </div>
            <div class="uk-width-1-1 uk-width-medium-1-2 text-container">
                <h1>L!ROCK</h1>
                @if (session('status'))
                    <div class="uk-alert uk-alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="uk-form uk-form-stacked" method="POST" action="{{ url('/password/email') }}">
                    <div class="uk-form-row {{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="uk-form-label" for="email">E-Mail:</label>
                        <div class="uk-form-controls">
                            <input class="uk-form-large uk-width-1-1" type="email" name="email" id="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="uk-alert uk-alert-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="uk-form-row">
                        {!! csrf_field() !!}
                        <button type="submit" class="uk-button uk-button-large uk-width-1-1">Начать сброс пароля</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
