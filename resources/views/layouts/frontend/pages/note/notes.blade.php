@extends('layouts.frontend.front-layout')
@section('title', __('pages.notes_tab'))
@section('content')
    @php
        $message = session('message');
        $locale = App::getLocale();
    @endphp
    <div class="header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page"><a
                        href="{{ url("$locale/notes") }}">@lang('breadcrumbs.note_list')</a></li>
                <li class="breadcrumb-item"><a href="{{ url("$locale/notes/create") }}">@lang('breadcrumbs.note_new')</a>
                </li>
                <li class="breadcrumb-item">@lang('breadcrumbs.note_edit')</li>
            </ol>
        </nav>
        <div>
            <a href="{{env('APP_URL')}}en/notes" class="{{App::getLocale() === 'en' ? 'active' : ''}}">@lang('navbar.language.english')</a>
            <a href="{{env('APP_URL')}}ru/notes" class="{{App::getLocale() === 'ru' ? 'active' : ''}}">@lang('navbar.language.russian')</a>
        </div>
    </div>
    <div class="main">
        <nav class="navbar navbar-light bg-white">
            {{--<a href="#" class="navbar-brand">Bootsbook</a>--}}
            <div>
                @if(\Illuminate\Support\Facades\Auth::user())
                    <a href="{{ url("$locale/logout") }}"
                       class='btn btn-danger headerBtn'>@lang('navbar.log_out')</a>
                    <a href="{{ url("$locale/notes/create") }}"
                       class='btn btn-primary mr-2 headerBtn'>@lang('navbar.new_note')</a>
                @else
                    <a href="{{ url('/login') }}" class="headerBtn">@lang('navbar.log_in')</a>";
                @endif
            </div>
            {{ Form::open([
            'class' => 'form-inline',
            'method' => 'GET'
        ]) }}
            <div class="input-group">
                {{ Form::text('search', null, ['class' => 'form-control', 'placeholder' => __('navbar.searchbar_placeholder')]) }}
                {{ Form::select('category', [
                        'all' => __('categories.all'),
                        'general' => __('categories.general'),
                        'work' => __('categories.work'),
                        'sport' => __('categories.sport'),
                        'relax' => __('categories.relax'),
                ], 'all', ['class' => 'form-control', 'id' => 'category']) }}
                <div class="input-group-append">
                    {{ Form::button('<i class="fa fa-search"></i>', ['class' => 'btn btn-outline-primary', 'type' => 'submit']) }}
                </div>
            </div>
            {{--{{ Form::submit('Search', ['class' => 'btn btn-primary']) }}--}}
            {{ Form::close() }}
        </nav>
        @if(isset($message))
            <p class={{$message['type'] === 'success' ? "text-success" : "text-danger"}}>{{ htmlentities($message['content']) }}</p>
        @endif
        <div class="table-responsive">
            <table class="table table-striped mt-4">
                <caption>@lang('table.caption')</caption>
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('table.header.title')</th>
                    <th scope="col">@lang('table.header.note')</th>
                    <th scope="col">@lang('table.header.author')</th>
                    <th scope="col">@lang('table.header.category')</th>
                    <th scope="col">@lang('table.header.visibility')</th>
                    <th scope="col">@lang('table.header.action')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($notes as $note)
                    <tr>
                        <th scope="row">{{ $note->id }}</th>
                        <td>{{ $note->title }}</td>
                        <td>{{ $note->text }}</td>
                        <td>{{ \App\Models\User::find($note->user_id)->username }}</td>
                        <td>{{ $note->category }}</td>
                        <td>{{ $note->visibility }}</td>
                        <td>
                            <a href='/{{$locale}}/notes/{{$note->uid}}/edit'
                               class='note__edit py-1 px-1 mr-2'>@lang('table.content.edit')</a>
                            {{ Form::open([
                                'url' => url("$locale/notes" . ($note ? '/' . $note->uid : '')),
                                'method' => 'delete',
                                'class' => 'd-inline'
                            ]) }}
                            {{ Form::submit(__('table.content.remove'), ['name' => 'remove', 'class' => 'note__remove btn-danger py-1 px-1']) }}
                            {{ Form::close() }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $notes->links() }}
        </div>
    </div>
@endsection
