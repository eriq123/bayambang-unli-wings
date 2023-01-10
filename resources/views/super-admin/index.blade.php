<div class="row gx-5 gy-5 mt-5">
    <div class="col-12">
        <div class="card content__card">
            <div class="card-body">
                <h1 class="mb-3">
                    Account management
                    <a class="mx-3 btn btn-outline-primary" href="{{ route('user.add') }}">
                        Add an admin
                    </a>
                </h1>

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="btn-group" role="group">
                            @foreach ($rolesFilter as $value)
                                <a href="{{ route('superAdminHome', ['slug' => $value]) }}"
                                    class="btn {{ Request::route('slug') == $value ? 'btn-primary text-dark' : 'btn-outline-primary' }}">
                                    {{ Str::ucfirst($value) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row gx-5 gy-5 mt-1 mb-5">
    @forelse ($users as $user)
        <div class="col-12 col-md-6">
            <div class="card content__card h-100">
                <div class="card-body">
                    <h2 class="card-title d-flex justify-content-between align-items-top">
                        {{ $user->name }}
                        <a class="mx-3 btn btn-outline-primary" href="{{ route('user.edit', ['id' => $user->id]) }}">
                            Update
                        </a>
                    </h2>
                    <p class="mb-2">
                        <b>Email:</b> {{ $user->email }}
                    </p>
                    <p class="mb-2">
                        <b>Role:</b> {{ $user->role->name ?? 'User' }}
                    </p>
                    <p class="mb-2">
                        <b>Shop:</b> {{ $user->shop->name ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-primary bg-dark text-primary mt-4" role="alert">
                There are no admins available.
            </div>
        </div>
    @endforelse
</div>
