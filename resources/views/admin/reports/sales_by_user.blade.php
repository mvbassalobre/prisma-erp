@extends("templates.admin")
@section('title',"Relatório de Vendas Por Usuário")
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin" class="link">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="/admin/reports/sales-by-user" class="link">Relatório de Vendas Por Usuário</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection
@section("content")
<sales-by-user
    :users="{{json_encode(\App\User::get())}}"
></sales-by-user>
@endsection