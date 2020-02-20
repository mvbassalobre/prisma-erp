@extends("templates.admin")
@section('title',"Home")
@section('content')
<h3>
    <i class="el-icon-s-home mr-2 font-weight-bolder"></i>
    Dashboard
</h3>
<small>{{Auth::user()->getSettings("mensagem-dashboard")}}</small>

<?php $user = Auth::user(); ?>
<home-dashboard
    :roles="{{json_encode($user->roles)}}"
    :user="{{json_encode($user)}}"
></home-dashboard>
@endsection