@extends('layouts.frontend.front-layout')
@section('title', 'Email Verification')
@section('content')
    <div class="verification-form-wrapper">
        <h1>Confirm your email to continue</h1>
        <div>
            <form action="/email/verification-notification" method="POST" class="d-inline-block">
                <button type="submit" class="btn" style="background-color: #dae0e5">Re-send link</button>
            </form>
            <a href="{{ url('/logout') }}" class='btn btn-danger headerBtn'>Logout</a>
        </div>
    </div>
@endsection
