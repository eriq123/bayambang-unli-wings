@extends('layouts.master')

@section('content')
    <div class="row gx-5 gy-5 mt-5">
        <div class="col-12">
            <div class="card content__card">
                <div class="card-body">
                    <h1 class="mb-3">
                        {{ isset($product) ? 'Edit' : 'Add product' }}
                        {{ $product->name ?? '' }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row gx-5 gy-5 my-5">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card content__card h-100">
                <form action="{{ route('product.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id ?? '' }}">

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name"
                                class="form-control bg-transparent {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                placeholder="Name" value="{{ $product->name ?? '' }}">

                            @if ($errors->has('name'))
                                <div class="invalid-feedback mt-0">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control bg-transparent {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                placeholder="Description">{{ $product->description ?? '' }}</textarea>

                            @if ($errors->has('name'))
                                <div class="invalid-feedback mt-0">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" name="price"
                                class="form-control bg-transparent {{ $errors->has('price') ? 'is-invalid' : '' }}"
                                placeholder="Name" value="{{ $product->price ?? '' }}">

                            @if ($errors->has('price'))
                                <div class="invalid-feedback mt-0">
                                    {{ $errors->first('price') }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image"
                                class="form-control bg-transparent {{ $errors->has('image') ? 'is-invalid' : '' }}"
                                placeholder="Name" value="{{ $product->image ?? '' }}">

                            @if ($errors->has('image'))
                                <div class="invalid-feedback mt-0">
                                    {{ $errors->first('image') }}
                                </div>
                            @endif
                        </div>
                        @if ($product->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" />
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between }}">
                            <div>
                                <button class="btn btn-success">
                                    Save
                                </button>
                                <a class="btn btn-secondary" href="{{ route('product.index') }}">
                                    Cancel
                                </a>
                            </div>
                            @if (isset($product))
                                <button class="btn btn-danger ml-auto" id="remove" type="button">
                                    Remove
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="module">
        $('#remove').on('click', function() {
            const form = $(this).closest('form');
            form.attr('action', '{{ route("product.destroy") }}');
            form.submit();
        });
    </script>
@endsection
