@extends('layouts.frontend.front-layout')
@section('title', __('pages.sign_up_tab'))
@section('content')
    <h1 class="text-center">@lang('pages.sign_up_page_title')</h1>
    {{ Form::open([
        'url' => url('/signup'),
        'method' => 'post',
        'class' => 'w-50 mx-auto'
    ]) }}
    <div class="form-group">
        {{ Form::label('username', __('forms.username_field_name')) }}
        {{ Form::text('username', null, ['id' => 'username', 'class' => 'form-control']) }}
        {!! $errors->first('username', '<p class="text-danger">:message</>') !!}
    </div>
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
    {{ Form::submit(__('forms.sign_up_btn_value'), ['name' => 'submit', 'class' => 'btn btn-primary']) }}
    {{ Form::close() }}
    <p class="text-center">@lang('pages.log_in_question') <a href="{{ url('/login') }}">@lang('pages.log_in_link_value')</a></p>
@endsection
