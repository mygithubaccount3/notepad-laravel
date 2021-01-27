@extends('layouts.frontend.front-layout')
@section('title', __('pages.note_page_title'))
@section('content')
    <div class="d-flex flex-column p-6 note__detail">
        <h1 class="align-self-center">@lang('pages.note_page_title')</h1>
        <p class="note__title">
            <span class="font-weight-bold">@lang('forms.note_edit_title_field_name'): </span>
            {{ $note->title }}
        </p>
        <p class="note__text">
            <span class="font-weight-bold">@lang('forms.note_edit_text_field_name'): </span>
            {{ $note->text }}
        </p>
        <p class="note__visibility">
            <span class="font-weight-bold">@lang('forms.note_edit_visibility_field_name'): </span>
            {{ $note->visibility }}
        </p>
        <p class="note__author">
            <span class="font-weight-bold">@lang('table.header.author'): </span>
            {{ \App\Models\User::find($note->user_id)->username }}
        </p>
    </div>
@endsection
