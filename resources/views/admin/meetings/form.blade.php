@extends("templates.admin")
@section('title',"Criar Reunião")
@section('breadcrumb')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin" class="link">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/admin/meetings" class="link">Reuniões</a>
                </li>
                @if(@$meeting)
                <li class="breadcrumb-item">
                    <a href="{{route('meeting.edit',$meeting->code)}}" class="link">{{$meeting->subject}}</a>
                </li>
                @else
                <li class="breadcrumb-item">
                    <a href="/admin/meetings/create" class="link">Criar Reunião</a>
                </li>
                @endif
            </ol>
        </nav>
    </div>
</div>
@endsection
@section('content')
<div class="row mt-2">
    <div class="col-12">
        <div class="d-flex flex-row justify-content-between mb-3">
            <h4 class="mb-1">
                <span class="el-icon-data-line mr-2"></span> {{@$meeting ? 'Editar': 'Criar'}} Reunião
            </h4>
        </div>
    </div>
</div>
<meeting-form :config='@json(@$config)'></meeting-form>
@endsection