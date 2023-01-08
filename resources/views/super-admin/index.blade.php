<div class="row gx-5 gy-5 mt-5">
    <div class="col-12">
        <div class="card content__card">
            <div class="card-body">
                <h1 class="mb-3">
                    Account management
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="row gx-5 gy-5 mt-1 mb-5">
    @forelse ($admins as $admin)
        <div class="col-12 col-md-6">
            <div class="card content__card h-100">
                <div class="card-body">
                    <h2 class="card-title d-flex justify-content-between align-items-top">
                        {{ $admin->name }}
                        <a class="mx-3 btn btn-outline-primary" href="{{ route('user.edit', ['id' => $admin->id]) }}">
                            Update
                        </a>
                    </h2>
                    <p class="mb-2">
                        <b>Role:</b> {{ $admin->role->name }}
                    </p>
                    <p class="mb-2">
                        <b>Shop:</b> {{ $admin->shop->name }}
                    </p>
                    <p class="mb-2">
                        <b>Email:</b> {{ $admin->email }}
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
