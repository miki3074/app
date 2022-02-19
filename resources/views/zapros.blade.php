@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ URL::previous() }}" class="link-dark" style="text-decoration: none;"> <h5>Назад</h5>
           </a>


        <div class="row justify-content-center">
            @foreach($data as $dat)
                <p class="text-center">
                 Код валюты: {{$dat['@attributes']['ID']}}<br>
                 Код страны: {{$dat['NumCode']}}<br>
                 Буквенный код страны: {{$dat['CharCode']}}<br>
                 Номинал: {{$dat['Nominal']}}<br>
                 Валюта: {{$dat['Name']}}<br>
                 Цена в рублях: {{$dat['Value']}}
                </p>
                <div class="col-xs-12 w-25"><hr></div>
            @endforeach
        </div>
    </div>
    <script src="https://cdn.lordicon.com/lusqsztk.js"></script>
@endsection
