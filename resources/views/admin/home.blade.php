@extends("templates.admin")
@section('title',"Home")
@section('content')
<h3>
    <i class="el-icon-s-home mr-2 font-weight-bolder"></i>
    Dashboard
</h3>
<small>{{Auth::user()->getSettings("mensagem-dashboard")}}</small>
<div class="row d-flex flex-wrap flex-row mt-3">
    @if(@$cards["now"])
        <div class="col-md-4 col-sm-12">
            <div class="card dashboard">
                <div class="card-header">Agora</div>
                <div class="card-body">
                    <div class="row">
                        @foreach($cards["now"] as $item)
                            <div class="col-md-6 col-sm-12">
                                <span><i class="{{$item['icon']}} mr-2"></i><a class="link" href="{{$item['route']}}">{{$item["label"]}} ({{$item['qty']}})</a></span>
                            </div>
                        @endforeach
                    </div>                
                </div>
            </div>
        </div>
    @endif
</div>
@endsection