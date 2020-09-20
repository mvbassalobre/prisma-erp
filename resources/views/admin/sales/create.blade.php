@extends("templates.admin")
@section('title',"Lançamento de Vendas")
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin" class="link">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="/admin/sales" class="link">Vendas</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="/admin/sales/create" class="link">Seleção de Cliente para Vendas</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection
@section("content")
<select-customer-to-sale></select-customer-to-sale>
@endsection