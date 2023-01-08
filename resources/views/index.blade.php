@extends('layouts.master')

@section('content')
    @auth
        @if (Auth::user()->role->name === 'Admin')
            @include('admin.index')
        @elseif (Auth::user()->role->name === 'Super Admin')
            @include('super-admin.index')
        @else
            <div class="alert alert-primary bg-dark text-primary mt-4" role="alert">
                You need to be an admin to be here. So... Are you lost?
            </div>
        @endif
    @endauth
@endsection
