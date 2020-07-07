@extends("templates.admin")
@section('title',"Relatório de Vendas")
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin" class="link">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="/admin/reports/sales-report" class="link">Relatório de Vendas</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection
@section("content")
<sales-report
    :teams="{{json_encode(\App\Http\Models\Team::get())}}"
></sales-report>
@endsection