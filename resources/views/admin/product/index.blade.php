@extends('layouts.master')

@section('content')
    <div class="row gx-5 gy-5 mt-5">
        <div class="col-12">
            <div class="card content__card">
                <div class="card-body">
                    <h1 class="mb-3">
                        Menu management
                        <a class="mx-3 btn btn-outline-primary" href="{{ route('user.add') }}">
                            Add product
                        </a>
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row gx-5 gy-5 mt-1 mb-5">
        @forelse ($products as $product)
            <div class="col-12 col-md-6">
                <div class="card content__card h-100">
                    <div class="card-body">
                        <h2 class="card-title d-flex justify-content-between align-items-top">
                            {{ $product->name }}
                            <a class="mx-3 btn btn-outline-primary"
                                href="{{ route('product.edit', ['id' => $product->id]) }}">
                                Update
                            </a>
                        </h2>
                        <p class="mb-2">
                            <b>Description:</b> {{ $product->description }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-primary bg-dark text-primary mt-4" role="alert">
                    There are no product/s available.
                </div>
            </div>
        @endforelse
    </div>
@endsection
