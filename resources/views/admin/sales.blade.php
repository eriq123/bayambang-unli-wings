@extends('layouts.master')

@section('content')
    @auth
        @if (Auth::user()->role->name === 'Admin')
            @include('admin.header')
            <div class="row gx-5 gy-5 mt-1 mb-5">
                <div class="col-12">
                    <div class="card content__card h-100">
                        <div class="card-body" id="sales" style="min-height: 500px;">

                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
@endsection
