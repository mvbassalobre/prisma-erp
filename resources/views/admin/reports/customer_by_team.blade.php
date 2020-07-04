@extends("templates.admin")
@section('title',"Relatório de Cliente por time")
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin" class="link">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="/admin/reports/customer-by-team" class="link">Relatório de Cliente por time</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection
@section("content")
<customers-by-team
    :teams="{{json_encode(\App\Http\Models\Team::get())}}"
></customers-by-team>
@endsection