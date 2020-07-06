@extends("templates.admin")
@section('title',"Relatório de Cliente por usuário")
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin" class="link">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="/admin/reports/customer-by-user" class="link">Relatório de Cliente por usuário</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection
@section("content")
<customers-by-user
    :users="{{json_encode(\App\User::get())}}"
></customers-by-user>
@endsection