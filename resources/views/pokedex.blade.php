@extends('layouts.app')

@section('content')

<h2>Pokédex</h2>

<input class="search-box" placeholder="Search Pokémon...">

<div class="grid">

@foreach($pokemon as $p)

    <div class="card">
        <div class="pokemon-name">
            {{ $p['name'] }}
        </div>

        <div class="type">
            {{ $p['type'] }}
        </div>

        <div class="stats">
            HP: {{ $p['hp'] }} <br>
            Attack: {{ $p['attack'] }} <br>
            Speed: {{ $p['speed'] }}
        </div>
    </div>

@endforeach

</div>

@endsection