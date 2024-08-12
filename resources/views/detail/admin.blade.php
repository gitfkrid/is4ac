@extends('layouts.dashboard')

@section('headcontent')
@endsection

@section('content')
    <h1>{{ $alat->nama_device }}</h1>
    <p>Topic MQTT: {{ $alat->topic_mqtt }}</p>
    <p>Status: {{ $alat->status }}</p>

    <h2>Data Sensor</h2>
    <p>Suhu: {{ $detailAlat->suhu }}</p>
    <p>Kelembaban: {{ $detailAlat->kelembaban }}</p>
    <p>Fosfin: {{ $detailAlat->fosfin }}</p>
@endsection

@section('script')
@endsection
