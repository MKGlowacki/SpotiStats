@extends('layouts.app')

@section('content')
    @if(empty($user))
    <div class="container">
    
        
        
        <a class="btn btn-success mb-4" href="/logIn">
            <h2>Połącz ze Spotify</h2>
        </a>
        
            
    </div>
    @elseif(!empty($user))
    
    <div class="container">
        <a class="btn btn-success mb-4" href="/logIn">
            <h2>Odśwież dane</h2>
        </a>
        <div class="container">
        <img src={{$user->image_url}}><br/>
        Nazwa: {{$user->name}}<br/>
        Email: {{$user->email}}<br/>
        Id: {{$user->id}}<br/>
        <div class="row justify-content-center">
            <h2> Najczęściej słuchane w  ciągu ostatniego miesiąca </h2>
            @foreach($tracks_short as $key => $track)
                <div class="container">
                    <p>
                        <img src={{$track->image_url}}><br/>
                        Tytuł: {{ $track->name }}<br/>
                        Album: {{ $track->album }}<br/>
                        Artysta: {{ $track->artist }}
                        
                    </p>
               </div>
            @endforeach
            <h2> Najczęściej słuchane w ciągu ostatnich 6 miesięcy </h2>
            @foreach($tracks_medium as $key => $track)
                <div class="container">
                    <p>
                    <img src={{$track->image_url}}><br/>
                        Tytuł: {{ $track->name }}<br/>
                        Album: {{ $track->album }}<br/>
                        Artysta: {{ $track->artist }}
                        
                    </p>
               </div>
            @endforeach
            <h2> Najczęściej słuchane w ciągu ostatniego roku</h2>
            @foreach($tracks_long as $key => $track)
                <div class="container">
                    <p>
                        <img src={{$track->image_url}}><br/>
                        Tytuł: {{ $track->name }}<br/>
                        Album: {{ $track->album }}<br/>
                        Artysta: {{ $track->artist }}
                        
                    </p>
               </div>
            @endforeach

        </div>
        </div>
    </div>
    @endif
    
@endsection