@extends('layouts.master')

@section('content')
    <div class="row gx-5 gy-5 mt-5">
        <div class="col-12">
            <div class="card content__card">
                <div class="card-body">
                    <h1 class="mb-3">
                        Edit
                        {{ $user->name }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row gx-5 gy-5 mt-5">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card content__card h-100">
                <form action="{{ route('user.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="role" class="form-label">Name</label>
                            <input type="text" name="name"
                                class="form-control bg-transparent {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                placeholder="Name" value="{{ $user->name }}">

                            @if ($errors->has('name'))
                                <div class="invalid-feedback mt-0">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email"
                                class="form-control bg-transparent {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="Email" value="{{ $user->email }}">
                            @if ($errors->has('email'))
                                <div class="invalid-feedback mt-0">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="role_id" class="form-label">Role</label>
                            <select class="form-select mb-1 text-primary bg-transparent" name='role_id'>
                                @foreach ($roles as $value)
                                    <option value="{{ $value->id }}" class="text-dark"
                                        {{ $value->id == $user->role_id ? 'selected' : '' }}>
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="shop_id" class="form-label">Shop</label>
                            <select class="form-select mb-1 text-primary bg-transparent" name='shop_id'>
                                @foreach ($shops as $value)
                                    <option value="{{ $value->id }}" class="text-dark"
                                        {{ $value->id == $user->shop_id ? 'selected' : '' }}>
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <div>
                                <button class="btn btn-success">
                                    Save
                                </button>
                                <a class="btn btn-secondary" href="/">
                                    Cancel
                                </a>
                            </div>
                            <button class="btn btn-danger" id="remove" type="button">
                                Remove
                            </button>
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
            form.attr('action', '{{ route("user.destroy") }}');
            form.submit();
        });
    </script>
@endsection
