@extends("templates.admin")
@section('title',"Relatório de Reuniões por Time")
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin" class="link">Dashboard</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="/admin/reports/meeting-by-team" class="link">Relatório de Reuniões por Time</a>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection
@section("content")
<meetings-by-team
    :teams="{{json_encode(\App\Http\Models\Team::get())}}"
    :rooms="{{json_encode(\App\Http\Models\MeetingRoom::get())}}"
    :status="{{json_encode(\App\Http\Models\MeetingStatus::get())}}"
></meetings-by-team>
@endsection