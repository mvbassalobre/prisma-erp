@extends("templates.admin")
@section('title',"Lançamento de ".$plural)
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin" class="link">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="{{ $resource_route }}" class="link">{{ ucfirst($plural) }}</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="/admin/sales/create" class="link">Seleção de Cliente</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection
@section("content")
<select-customer-to-sale type="{{ $type }}" plural="{{ $plural }}"></select-customer-to-sale>
@endsection
