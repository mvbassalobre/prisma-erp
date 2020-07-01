@extends("templates.admin")
@section('title',"Home")
@section("breadcrumb")
<nav aria-label="breadcrumb">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="/admin" class="link">Dashboard</a>
            </li> 
            <li class="breadcrumb-item">
                <a href="{{route('admin.parameters')}}" class="link">Parâmetros</a>
            </li>
        </ol>
    </nav>
</nav>
@endsection
@section('content')
    <h3>
        <span class="el-icon-s-tools mr-2 font-weight-bolder"></span>
        Parâmetros
    </h3>
    
    <user-parameters
        :settings="{{json_encode($settings)}}"
    >
    </user-parameters>
@endsection