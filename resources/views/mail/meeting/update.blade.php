@component('mail::message',["img" => @json_decode($meeting->responsible->getSettings('logo-pequeno'))[0]])
@slot('header')
@if($meeting->wasRecentlyCreated)
Reunião Criada
@else
Reunião Atualizada
@endif
@endslot



@if(trim($body))
## Mensagem de atualização:
{!!$body!!}
@endif


## Status: {{$meeting->status->name}}

## Assunto
{{$meeting->subject}}

## Data e Horário
{{$meeting->getMeetingTimeText()}}

## Local
{{$meeting->room->f_address}}

@component('mail::button', ['url' => $meeting->makeEventLink()])
Adicionar na Agenda
@endcomponent
@endcomponent