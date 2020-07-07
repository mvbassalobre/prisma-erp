@extends("templates.admin")
@section('title',"Relatório de Vendas por Times")
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin" class="link">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="/admin/reports/sales-by-team" class="link">Relatório de Vendas por Time</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection
@section("content")
<sales-by-team
    :teams="{{json_encode(\App\Http\Models\Team::get())}}"
></sales-by-team>
@endsection