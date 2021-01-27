@extends('layouts.frontend.front-layout')
@section('title', __('pages.log_in_tab'))
@section('content')
    <h1 class="text-center">@lang('pages.log_in_page_title')</h1>
    @php
        $message = session('message');
    @endphp
    @if(isset($message))
        <p class="text-success text-center">{{ htmlentities($message) }}</p>
    @endif
    {{ Form::open([
        'url' => url('/login'),
        'method' => 'post',
        'class' => 'w-50 mx-auto'
    ]) }}
    <div class="form-group">
        {{ Form::label('email', __('forms.email_field_name')) }}
        {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control']) }}
        {!! $errors->first('email', '<p class="text-danger">:message</>') !!}
    </div>
    <div class="form-group">
        {{ Form::label('password', __('forms.pass_field_name')) }}
        {{ Form::password('password', ['id' => 'password', 'class' => 'form-control']) }}
        {!! $errors->first('password', '<p class="text-danger">:message</>') !!}
    </div>
    {{ Form::submit(__('forms.log_in_btn_value'), ['name' => 'submit', 'class' => 'btn btn-primary']) }}
    {{ Form::close() }}
    <p class="text-center">@lang('pages.sign_up_question') <a href="{{ url('/signup') }}">@lang('pages.sign_up_link_value')</a></p>
@endsection
