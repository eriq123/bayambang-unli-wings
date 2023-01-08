@extends('layouts.master')

@section('content')
    @auth
        @if (Auth::user()->role->name === 'Admin')
            @include('admin.header')
            <div class="row gx-5 gy-5 mt-1 mb-5">
                <div class="col-12">
                    <div id="sales" style="min-height: 500px; width: 100%;"></div>
                </div>
            </div>
        @endif
    @endauth
@endsection
@section('js')
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        window.onload = function() {
            var chart = new CanvasJS.Chart("sales", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: <?php echo json_encode(ucfirst(Request::route('slug'))); ?> + " Sales"
                },
                axisX: {
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                axisY: {
                    title: "PHP",
                    includeZero: true,
                    crosshair: {
                        enabled: true,
                        snapToDataPoint: true
                    }
                },
                toolTip: {
                    enabled: false
                },
                data: [{
                    type: "area",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });

            chart.render();
        }
    </script>
@endsection
