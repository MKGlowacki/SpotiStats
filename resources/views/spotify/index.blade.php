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
        <img src={{$user->images[0]->url}}><br/>
        {{$user->display_name}}<br/>
        {{$user->email}}<br/>
        {{$user->id}}<br/>
        <div class="row justify-content-center">
            <h2> Najczęściej słuchane w  ciągu ostatniego miesiąca </h2>
            @foreach($tracks_short as $key => $track)
                <div class="container">
                    <p>
                        <img src={{$track->album->images[2]->url}}><br/>
                        Tytuł: {{ $track->name }}<br/>
                        Album: {{ $track->album->name }}<br/>
                        Artysta: @foreach($track->artists as $key => $artist)
                            {{ $artist->name }}
                        @endforeach
                    </p>
               </div>
            @endforeach
            <h2> Najczęściej słuchane w ciągu ostatnich 6 miesięcy </h2>
            @foreach($tracks_medium as $key => $track)
                <div class="container">
                    <p>
                        <img src={{$track->album->images[2]->url}}><br/>
                        Tytuł: {{ $track->name }}<br/>
                        Album: {{ $track->album->name }}<br/>
                        Artysta: @foreach($track->artists as $key => $artist)
                            {{ $artist->name }}, 
                        @endforeach
                    </p>
               </div>
            @endforeach
            <h2> Najczęściej słuchane w ciągu ostatniego roku</h2>
            @foreach($tracks_long as $key => $track)
                <div class="container">
                    <p>
                        <img src={{$track->album->images[2]->url}}><br/>
                        Tytuł: {{ $track->name }}<br/>
                        Album: {{ $track->album->name }}<br/>
                        Artysta: @foreach($track->artists as $key => $artist)
                            {{ $artist->name }}, 
                        @endforeach
                    </p>
               </div>
            @endforeach

        </div>
        </div>
    </div>
    @endif
    
@endsection