@extends('layouts.master')

@section('content')
    <section class="login row">
        <div class="col-12">
            <div class="card login__card">
                <div class="card-body py-4 px-3">
                    <form method="POST" action="{{ route('login.post') }}" novalidate>
                        @csrf
                        <fieldset>
                            <div class="d-flex justify-content-center">
                                <h1
                                    class="text-uppercase text-center h1 mb-4 display-print-inline border-bottom border-2 border-white">
                                    Login
                                </h1>
                            </div>

                            <div class="input-group input-group-lg {{ $errors->has('email') ? 'mb-0' : 'mb-4' }}">
                                <input type="email" name="email"
                                    class="form-control rounded-pill login__input text-light {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    placeholder="Email" required>
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback mt-0">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>

                            <div class="input-group input-group-lg {{ $errors->has('password') ? 'mb-0' : 'mb-4' }}">
                                <input type="password" name="password"
                                    class="form-control rounded-pill login__input text-light {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="Password" required>
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback mt-0">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-center align-items-center mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" id="superAdminCheckbox"
                                        name="isSuperAdmin">
                                    <label class="form-check-label" for="superAdminCheckbox">
                                        Login as Super Admin
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-center align-items-center mt-3">
                                <button type="submit"
                                    class="btn btn-primary btn-lg text-uppercase rounded-pill login__submit text-primary bg-dark">
                                    Login
                                </button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
