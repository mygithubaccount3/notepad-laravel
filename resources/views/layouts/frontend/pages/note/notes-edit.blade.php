@extends('layouts.frontend.front-layout')
@section('title', __('pages.note_edit_tab'))
@section('content')
    <?php
    use App\Models\Note;
    /** @var Note $note */
    $note = $note ?? null;
    ?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a
                    href="{{ url('/notes') }}">@lang('breadcrumbs.note_list')</a></li>
            <li class="breadcrumb-item {{ $note == null ? "active" : ''}}"><a
                    href="{{ url('/notes/create') }}">@lang('breadcrumbs.note_new')</a></li>
            <li class="breadcrumb-item {{ $note ? "active" : ''}}">@lang('breadcrumbs.note_edit')</li>
        </ol>
    </nav>
    <h1 class="text-center">@lang('pages.note_edit_tab')</h1>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="en-tab" data-toggle="tab" href="#en"
               role="tab" aria-controls="en" aria-selected="true">EN</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ru-tab" data-toggle="tab" href="#ru"
               role="tab" aria-controls="ru" aria-selected="false">RU</a>
        </li>
    </ul>
    {{ Form::open([
        'url' => url('/' . App::getLocale() . '/notes' . ($note ? '/' . $note->uid : '')),
        'method' => isset($note) ? 'patch' : 'post',
        'class' => 'd-flex flex-column w-25 m-auto'
    ]) }}
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="en" role="tabpanel" aria-labelledby="en-tab">
            <div class="form-group">
                {{ Form::label('title_en', __('forms.note_edit_title_field_name') . ' (EN)') }}
                {{ Form::text('title_en', $note->translations[0]->title ?? null, ['class' => 'form-control', 'id' => 'title_en']) }}
                {!! $errors->first('translations.en.title', '<p class="text-danger">:message</>') !!}
            </div>
            <div class="form-group">
                {{ Form::label('text_en', __('forms.note_edit_text_field_name') . ' (EN)') }}
                {{ Form::textarea('text_en', $note->translations[0]->text ?? null, ['class' => 'form-control', 'cols' => '34']) }}
                {!! $errors->first('translations.en.text', '<p class="text-danger">:message</>') !!}
            </div>
        </div>
        <div class="tab-pane fade" id="ru" role="tabpanel" aria-labelledby="ru-tab">
            <div class="form-group">
                {{ Form::label('title_ru', __('forms.note_edit_title_field_name') . ' (RU)') }}
                {{ Form::text('title_ru', $note->translations[1]->title ?? null, ['class' => 'form-control', 'id' => 'title_ru']) }}
                {!! $errors->first('translations.ru.title', '<p class="text-danger">:message</>') !!}
            </div>
            <div class="form-group">
                {{ Form::label('text_ru', __('forms.note_edit_text_field_name') . ' (RU)') }}
                {{ Form::textarea('text_ru', $note->translations[1]->text ?? null, ['class' => 'form-control', 'cols' => '34']) }}
                {!! $errors->first('translations.ru.text', '<p class="text-danger">:message</>') !!}{{--after fixing storing/editing in web version, check this--}}
            </div>
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('visibility', __('forms.note_edit_visibility_field_name') . ':') }}
        {{ Form::select('visibility', [
            null => __('forms.field_default_value'),
            'public' => __('forms.visibility_field_public_value'),
            'private' => __('forms.visibility_field_private_value')
        ], $note->visibility ?? 'private', ['class' => 'form-control']) }}
        {!! $errors->first('visibility', '<p class="text-danger">:message</>') !!}
    </div>
    @if ($note && $note->visibility === 'private')
        <div class="form-group">
            {{ Form::label('share_user_email', __('forms.share_with_field_name')) }}
            {{ Form::email('share_user_email', null, ['class' => 'form-control']) }}
            {!! $errors->first('share_user_email', '<p class="text-danger">:message</>') !!}
        </div>
    @endif
    <div class="form-group">
        {{ Form::label('category', __('forms.note_edit_category_field_name')) }}
        {{ Form::select('category', [
            null => __('forms.field_default_value'),
            'general' => __('categories.general'),
            'work' => __('categories.work'),
            'sport' => __('categories.sport'),
            'relax' => __('categories.relax'),
        ], $note->category ?? 'general', ['class' => 'form-control', 'id' => 'category']) }}
        {!! $errors->first('category', '<p class="text-danger">:message</>') !!}
    </div>
    <div class="mx-auto">
        {{ Form::button($note ? __('forms.update_note_btn_value') : __('forms.create_note_btn_value'), ['type' => 'submit', 'class' => 'btn btn-primary']) }}
        {{ Html::link('/notes', __('forms.cancellation_link_value'), ['class' => 'btn']) }}
    </div>
    {{ Form::close() }}
@endsection
