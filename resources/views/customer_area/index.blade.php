@extends("templates.default")
@section('title',"Area do Cliente")
@section('body')
<?php
    $small_logo = @json_decode($tenant->getSettings("logo-pequeno"))[0];
    $big_logo = @json_decode($tenant->getSettings("logo-grande"))[0];
    $smalllogo = @$small_logo ? $small_logo : asset('assets/images/logo.png');
?>
@section('favicon',$smalllogo)
    <customer-area
        logo="{{$smalllogo}}"
    ></customer-area>
@endsection
