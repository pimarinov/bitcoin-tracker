@extends('layouts.app')

@section('content')
<div class="my-4">
    <h1>{{ config('app.name') }}</h1>
    <div>
        <canvas id="bitcoin-chart" class="w-100 bg-light" style="max-height: 40vh;"></canvas>
    </div>
    <!--script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script>
        const updateBitcoinSnapshots = "{{ route('bitcoin.snapshots') }}";
        const dataJson = @json($snapshots);
        const chartRefreshInterval = ( {{ (float) $snapshotInterval }} * 1000 ); // 2.5ms

        function addDates(dataJson) {
            var datePrepared = [];
            for (let i = 0; i < dataJson.length; i++) {
                dataJson[i].t = new Date((dataJson[i].x));
                datePrepared.push(dataJson[i]);
            }
            return datePrepared;
        }

        const data = {
            datasets: [{
                label: 'Bitcoin Price (USD) -- last 5 minutes',
                data: addDates(dataJson)/*[
                    {x: '2022-10-12T13:00:00', y: 20000},
                    {x: '2022-10-12T14:00:00', y: 200},
                    {x: '2022-10-12T15:00:00', y: 20000},
                    {x: '2022-10-12T16:00:00', y: 3000},
                    {x: '2022-10-12T17:00:00', y: 20000},
                    {x: '2022-10-12T18:00:00', y: 64000},
                    {x: '2022-10-12T19:00:00', y: 20000},
                    {x: Date.now(), y: 18000},
                ]*/,
                borderColor: 'DodgerBlue',
                borderWidth: 2,
                pointRadius: 2
            }]
        };
        const config = {
            type: 'line',
            data,
            options: {
                scales: {
                    x: {
                        parse: false,
                        type: 'timeseries', // @try 'timeseries'
                        time: {
                            unit: 'second'
                        }
                    },
                    /*y: {
                        beginAtZero: true
                    }*/
                },
                /*animation: {
                    easing: 'linear',
                    duration: 300
                }*/
            }
        };
        const myChart = new Chart(
                document.getElementById('bitcoin-chart'),
                config
            );

        const updateChartTimeout = setInterval(function(){
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                var jsonFreshData = JSON.parse(this.responseText)
                myChart.data.datasets[0].data = jsonFreshData;
                myChart.update('none');
                //myChart.shift(/*'none'*/);
            };
            xhttp.open("GET", updateBitcoinSnapshots, true);
            xhttp.send();
        }, chartRefreshInterval);
    </script>
    <div class="container mt-5">
        <form method="post" action="{{ route('bitcoin.subscribe-for-price-reach') }}">
            @csrf
            <div class="card mt-5 border-secondary border border-2">
                <div class="card-header py-3 text-secondary">
                    <big>Email me when the price exceeds the stated amount</big>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                            <strong>Error!</strong>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session()->has('status'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            <strong>Thank You!</strong>
                            <div>{!! session()->get('status') !!}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                    <div class="card-body">
                        <div class="form-floating mb-3">
                            <input type="text" name="email" value="{{ old('email') }}"
                                class="form-control form-control-sm" id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating">
                            <input type="number" name="price" value="{{ old('price') }}"
                                class="form-control form-control-sm" id="floatingPrice" step="0.01" placeholder="Price in USD">
                            <label for="floatingPrice">Reached price value</label>
                        </div>
                    </div>
                    <div class="card-footer bg-white py-2">
                        <button class="btn btn-md btn-secondary btn-lg float-end" type="submit" aria-disabled="true">Submit</button>
                    </div>
            </div>
        </form>
    </div>
</div>
@endsection
