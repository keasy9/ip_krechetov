@extends('layout')
@section('content')
    <x-content::gallery
        :galleryId="$data['promo-slider']"
        defaultTemplate="slider"
    />
@endsection
