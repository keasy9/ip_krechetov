@extends('layout')
@section('content')
    @isset($data['promo-slider'])
        <x-content::gallery
            :galleryId="$data['promo-slider']"
            defaultTemplate="slider"
        />
    @endisset
    @isset($data['cards'])
        <x-content::gallery
            :galleryId="$data['cards']"
            defaultTemplate="cards"
        />
    @endisset
    @isset($data['socials'])
        <x-main::socials :data="$data['socials']" />
    @endisset
    @isset($data['gallery'])
        <x-content::gallery
            :galleryId="$data['gallery']"
            defaultTemplate="gallery"
        />
    @endisset
    @isset($data['contacts'])
        <x-main::contacts :data="$data['contacts']" />
    @endisset
@endsection
