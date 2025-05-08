@extends('layouts.app')

@section('title', 'Wedstrijden - ' . $category)

@section('content')
    <h1>Wedstrijden in de categorie: {{ $category }}</h1>

    @if($matches->isEmpty())
        <p>Er zijn geen wedstrijden in deze categorie.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Locatie</th>
                    <th>Check-in Tijd</th>
                    <th>Kick-off Tijd</th>
                    <th>Limiet</th>
                </tr>
            </thead>
            <tbody>
                @foreach($matches as $match)
                    <tr>
                        <td>{{ $match->name }}</td>
                        <td>{{ $match->location }}</td>
                        <td>{{ $match->checkin_time }}</td>
                        <td>{{ $match->kickoff_time }}</td>
                        <td>{{ $match->limit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection