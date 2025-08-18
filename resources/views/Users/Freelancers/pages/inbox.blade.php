@extends('dashboards.freelancer.dashboard')

@section('body')
<style>
    .welcome-message {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
    .heading{
        text-align: center;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }    
</style>
<div class="welcome-message">Welcome back, {{Auth::User()->name}}! 👋</div>

<h1 class="heading">Hi there I stand for <b>Inbox</b></h1>
@endsection