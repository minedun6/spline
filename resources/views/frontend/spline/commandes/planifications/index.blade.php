@extends('frontend.layouts.app')

@section('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css"/>
@endsection

@section('content')
@if(is_admin())
<div class="row-md-12">
    <div class="portlet light bordered">
        <div class="portlet-title">
            <div class="caption">
                <i class="icon-settings font-dark"></i>
                <span class="caption-subject font-dark sbold uppercase"> Mes planifications</span>
            </div>
            <div class="actions">
                <a href="{{route('frontend.commandes.planifications.create')}}" class="btn btn-success">Planifier une date de pose</a>
            </div>
        </div>
        <div class="portlet-body form" >
          <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="planifications-table">
                    <thead>
                        <tr>
                            <th>Poseur</th>
                            <th>Commande</th>
                            <th>Dates</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div> 
@endif 
<div class="row-md-12">
    <div class="portlet light portlet-fit bordered calendar">
        <div class="portlet-title">
            <div class="caption">
                <i class=" icon-layers font-green"></i>
                <span class="caption-subject font-green sbold uppercase">Calendrier</span>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row">
                <div class="col-md-3 col-sm-12 ">
                    <div class="portlet light portlet-fit bordered calendar">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-users font-red"></i>
                                <span class="caption-subject font-red sbold uppercase">The team</span>
                            </div>
                        </div>
                        <div class="portlet-body tasks-widget">
                            <!-- BEGIN DRAGGABLE EVENTS PORTLET-->
                            <div class="scroller" style="height: 312px;" data-always-visible="1" data-rail-visible1="1">
                                <!-- START TASK LIST -->
                                <ul class="task-list">
                                    @foreach($poseurs as $p)
                                    @if(is_admin() or $p->id == Auth::user()->id)
                                    <li>
                                        <div class="task-checkbox">
                                            <input type="checkbox" class="equipe" checked="checked" value="{{$p->id}}" /> </div>
                                            <div class="task-title">
                                                <span class="label label-sm" style="
                                                    background-color: #{{json_decode($p->extras, true)['color']}};
                                                ">{{$p->name.' '.$p->lastname}}</span>
                                            </div>
                                        </li>
                                    @endif
                                    @endforeach
                                    </ul>
                                    <!-- END START TASK LIST -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9 col-sm-12">
                        <div id="calendar" class="has-toolbar"> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @endsection

    @section('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/locale/fr.js"></script>
    <script src="{{asset('js/calendar.js')}}"></script>
    <script>
        $(function() {
            $('#planifications-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
                },
                ajax: '{!! route('frontend.commandes.planifications.all') !!}',
                columns: [
                    { data: 'poseur', name: 'poseur' },
                    { data: 'commande', name: 'commande' },
                    { data: 'dates', name: 'dates' },
                    { data: 'actions' },
                ]
            });
            if ($('#calendar').parents(".portlet").width() <= 720) {
                $('#calendar').addClass("mobile");
                h = {
                    left: 'title, prev, next',
                    center: '',
                    right: 'today,month,agendaWeek,agendaDay'
                };
            } else {
                $('#calendar').removeClass("mobile");
                h = {
                    left: 'title',
                    center: '',
                    right: 'prev,next,today,month,agendaWeek,agendaDay'
                };
            }
            $('#calendar').fullCalendar({
                lang: 'fr',
                header: h,
                eventSources: [
                    {
                        url: "{!! route('frontend.commandes.planifications.getPlanificationForFullCallendar') !!}", 
                    }
                ],
                eventRender: function eventRender( event, element, view ) {
                    element.find('.fc-time').remove();
                    element.find('.fc-title').html(element.find('.fc-title').text());           

                    return filter(event);
                },
                eventClick: function(event) {
                    if (event.url) {
                        location(event.url);
                        return false;
                    }
                }
            });
            $('.equipe').on('change',function(){
                $('#calendar').fullCalendar('rerenderEvents');
            });

            function filter(calEvent) {
              var vals = [];
              $('.equipe:checked').each(function() {
                    vals.push($(this).val());
              });
              return vals.indexOf(calEvent.equipe) !== -1;
          }
      });
  </script>
  @endsection