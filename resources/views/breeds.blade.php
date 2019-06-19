@extends('template.app')

@section('title', 'Page Title')

@section('content')
    <div class="row">
        @foreach ($array as $item)
            <div class="card" style="width: 18rem;">
                <img src="{{ $item['urlImage'] }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">{{ $item['name'] }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $item['temperament']}}</h6>
                    <p class="card-text">{{$item['description']}}</p>
                    <a href="{{ $item['wikipedia_url']}}" target="_blank">See More</a>
                    <p class="card-text"></p>
                </div>
    </div>
    @endforeach
    </div>
@endsection




