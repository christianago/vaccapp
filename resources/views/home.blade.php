<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }} | Greek Vaccination data</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @include('css')
    </head>
    <body>
     
        <div class="container">

            <div class="alert alert-primary text-center" role="alert">
                Greek Vaccination data
            </div>

            <img id="loading" src="loading.gif" />

            <div id="error" class="alert alert-danger text-center" role="alert"></div>

            <div class="row offset-3 mb-5">
                <div class="col-4">
                    <input class="form-control" type="text" id="start" placeholder="Start date" />
                </div>
                <div class="col-4">
                    <input class="form-control" type="text" id="end" placeholder="End date" />
                </div>
            </div>
                
            <div style="height: 500px;">
                <canvas class="chart" id="chart"></canvas>
            </div>
          
        </div>

        @include('js')
    </body>
</html>
