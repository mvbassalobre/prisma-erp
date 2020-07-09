@extends("templates.admin")
@section('title',"Home")
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin" class="link">Dashboard</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection
@section("content")
<?php $user = Auth::user(); ?>
<home-dashboard
    :roles="{{json_encode($user->roles)}}"
    :user="{{json_encode($user)}}"
    :params="{{json_encode($_GET)}}"
    default_range="{{$default_range}}"
></home-dashboard>
@endsection