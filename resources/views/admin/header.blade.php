<div class="row gx-5 gy-5 mt-5">
    <div class="col-12">
        <div class="card content__card">
            <div class="card-body">
                <h1 class="mb-3">
                    {{ Auth::user()->shop->name }}
                </h1>

                <div class="row">
                    <div class="col-12 col-lg-6">
                        <h5>Orders</h5>
                        <div class="btn-group" role="group">
                            @foreach ($status as $value)
                                <a href="/{{ Auth::user()->shop->slug }}/{{ $value->slug }}"
                                    class="btn {{ Request::route('slug') == $value->slug ? 'btn-primary text-dark' : 'btn-outline-primary' }}">
                                    {{ $value->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 mt-4 mt-lg-0">
                        <h5>Sales</h5>

                        <div class="btn-group" role="group">
                            <a href="/sales"
                                class="btn {{ Request::route('slug') == 'daily' ? 'btn-primary text-dark' : 'btn-outline-primary' }}">
                                Daily
                            </a>
                            <a href=""
                                class="btn {{ Request::route('slug') == 'weekly' ? 'btn-primary text-dark' : 'btn-outline-primary' }}">
                                Weekly
                            </a>
                            <a href=""
                                class="btn {{ Request::route('slug') == 'monthly' ? 'btn-primary text-dark' : 'btn-outline-primary' }}">
                                Monthly
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
