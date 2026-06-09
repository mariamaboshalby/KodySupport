@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="site-container" style="padding-top:2rem; padding-bottom:3rem">
    <div class="card" style="padding:1.5rem">
        <p style="color:var(--color-text-secondary)">{{ __("You're logged in!") }}</p>
    </div>
</div>
@endsection
