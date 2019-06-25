@extends('frontend.layouts.app')

@section('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
@endsection

@section('content')

    <div class="row-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase"> La pharmacie #{{$pharmacie->id}}</span>
                </div>
                <div class="actions">

                </div>
            </div>	
            <div class="portlet-body form" >
            	<div class="portlet box green">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-info"></i> Détail de la pharmacie #{{$pharmacie->id}}</div>
                        <div class="actions">
                            
                        </div>
                    </div>
                    {{-- 
                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                      <!-- Indicators -->
                      <ol class="carousel-indicators">
                        @foreach($vitrines as $k => $vitrine)
                        <li data-target="#myCarousel" data-slide-to="{{$k}}" @if($k == 0) class="active" @endif></li>
                        @endforeach
                      </ol>

                      <!-- Wrapper for slides -->
                      <div class="carousel-inner" role="listbox">
                        @foreach($vitrines as $k => $vitrine)
                        <div class="item @if($k == 0) active @endif">
                          <img src="{{ asset($vitrine->path)}}" alt="" >
                        </div>
                        @endforeach
                      </div>

                      <!-- Left and right controls -->
                      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                      </a>
                      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                      </a>
                    </div> --}}
                    <div class="portlet-body">
                            <div id="gallery" class="col-md-12">
                                @foreach($vitrines as $k => $vitrine)
                                    <img data-src="{{ asset($vitrine->path)}}" alt="">
                                @endforeach
                            </div>
                        <div id="gmap_basic" class="gmaps" style="position: relative; overflow: hidden;"></div>
                        
						<hr>
                    	<div class="row">
                    		<div class="col-md-4">
                                <ul>
                                    <li><strong>Nom: </strong>{{$pharmacie->nom}}</li>
                                    <li><strong>Adresse: </strong>{{$pharmacie->adresse}}</li>
                                    <li><strong>Secteur: </strong>{{$pharmacie->secteur}}</li>
                                    <li><strong>Téléphone: </strong>{{$pharmacie->telephone}}</li>
                                    @if($pharmacie->note != '')
                                    <li><strong>Note: </strong>{{$pharmacie->note}}</li>
                                    @endif
                                </ul>
                            </div>
                            @if(!is_client() and isset(json_decode($pharmacie->extras, true)['mesures']) )
                            <div class="col-md-4">
                                <strong>Mesures:</strong> 
                                <ul>
                                @foreach(json_decode($pharmacie->extras, true)['mesures'] as $mesures)
                                <li><strong>L:</strong> {{$mesures['width'] }}, <strong>H:</strong> {{$mesures['height'] }}</li>
                                @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="col-md-4">
                            	<table class="table table-bordered table-advance">
                            		<thead>
                            			<tr>
                            				<th><i class="fa fa-user"></i> Client</th>
                            				<th><i class="fa fa-info"></i> Nom délégué</th>
                            			</tr>
                            		</thead>
                            		<tbody>
                            			@foreach($delegues as $d)
                            			<tr>
                            				<td><a class="font-red" href="{{route('frontend.delegues.assign.remove', ['id' => $d->id])}}"><i class="fa fa-close"></i></a> {{$d->client->name}}</td>
                            				<td>{{$d->delegue->firstname.' - '.$d->delegue->phone}}</td>
                            			</tr>
                            			@endforeach
                            		</tbody>
                            	</table>
                            </div>
                    	</div>
                    	<div class="row">
                    		<div class="col-md-12">
                    			
                    		</div>
                    	</div>
                	</div>

				</div>
			</div>
		</div>
	</div>
@endsection

@section('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.20&key=AIzaSyBQS5hST_G92pNFu5vOTFpdMDjfsO3J1pQ"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.js"></script>

    <script>
        var map = new GMaps({
          div: '#gmap_basic',
          lat: @if($pharmacie->lat)  {{$pharmacie->lat}} @else 36.9189700 @endif,
          lng: @if($pharmacie->long) {{$pharmacie->long}} @else 36.9189700 @endif,
          draggable: false,
          zoom: 14,
          scrollwheel: false
        });
        map.addMarker({
          lat: @if($pharmacie->lat)  {{$pharmacie->lat}} @else 36.9189700 @endif,
          lng: @if($pharmacie->long) {{$pharmacie->long}} @else 36.9189700 @endif,
        });
        var g = $('#gallery').portfolio({
            showArrows: false,
            logger: false
        });
        g.init();
		$(function () {
            setTimeout(function() { 
                $("#client_id").selectpicker();
                $("#delegue_id").selectpicker();
                $(".bootstrap-select").click(function () {
                     $(this).addClass("open");
                }); 

                $('#client_id').change(function (){
                	$.ajax({
                		url: "{{route('frontend.pharmacie.getDelegues')}}",
                		data: {
                			client_id: $('#client_id').val()
                		},
                		success: function (result){
                			$('#delegue_id').empty();
                			result.forEach(function(d, i){
                				$("#delegue_id").append('<option value="'+d.id+'">'+d.firstname+' '+d.lastname+' - '+d.phone+'</option').selectpicker('refresh');;
                			});
                		}
                	})
                });
            }, 500);
		});
	</script>
@endsection
