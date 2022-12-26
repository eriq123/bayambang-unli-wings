@extends('layouts.master')

@section('content')
    @auth
        @if (Auth::user()->role->name === 'Admin')
            @include('admin.header')

            <div class="row gx-5 gy-5 mt-1 mb-5 orders">
                @forelse ($orders as $order)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card content__card h-100">
                            <div class="card-body">

                                <h2 class="card-title d-flex justify-content-between align-items-top">
                                    #{{ $order->id }}

                                    <form action="{{ route('order.destroy', $order->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn p-0">
                                            @include('partials.svg.trash')
                                        </button>
                                    </form>
                                </h2>

                                <div class="status-{{ $order->id }}">
                                    <div class="d-inline-block">
                                        <form action="{{ route('order.update', ['id' => $order->id]) }}" method="POST">
                                            @csrf
                                            <select class="form-select select__status mb-1 text-primary bg-transparent"
                                                data-id={{ $order->id }} name='status_id'>
                                                @foreach ($status as $value)
                                                    <option value="{{ $value->id }}" class="text-dark"
                                                        {{ $value->id == $order->status_id ? 'selected' : '' }}>
                                                        {{ $value->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>

                                    <p class="card-text d-inline-block ml-3">
                                        {{ $order->updated_at->diffForHumans() }}
                                    </p>
                                </div>

                                <ul class="mt-3">
                                    @foreach ($order->products as $product)
                                        <li>
                                            {{ $product->pivot->quantity }} x {{ $product->title }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-footer">
                                <p class="mb-2">
                                    {{ $order->user->name }}
                                </p>
                                <p class="mb-2">
                                    {{ $order->user->contact_number }}
                                </p>
                                <p class="mb-0">
                                    {{ $order->user->address }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-primary bg-dark text-primary mt-4" role="alert">
                            No order/s at the moment.
                        </div>
                    </div>
                @endforelse
            </div>
        @elseif (Auth::user()->role->name === 'Super Admin')
            <div class="alert alert-primary bg-dark text-primary mt-4" role="alert">
                {{ Auth::user()->role->name }} page is a work in progress... Can you believe that?
            </div>
        @else
            <div class="alert alert-primary bg-dark text-primary mt-4" role="alert">
                You need to be an admin to be here. So... Are you lost?
            </div>
        @endif
    @endauth
@endsection
