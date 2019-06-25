@extends('frontend.layouts.app')

@section('after-styles')
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.1/css/select.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
@endsection

@section('content')
<div class="row-md-12">
    <div class="portlet-body form" >
      <div class="row">
        <div class="col-md-12">
        <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-equalizer font-blue-hoki"></i>
                        <span class="caption-subject font-blue-hoki bold uppercase">Planifier une date de pose</span>
                    </div>
                    <div class="tools">
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
           			{{ Form::open(['route' => 'frontend.commandes.planifications.store', 'id' => 'planificationsModel','class' => 'horizontal-form']) }}
                        <div class="form-body">
                        	<div class="row">
                        		<div class="col-md-12">
                        			<table class="table table-striped table-bordered table-hover" id="commandes-table">
					                    <thead>
					                        <tr>
					                        	<th width="20"></th>
                                                <th>Commande</th>
					                            <th>Dates</th>
					                            <th>Actions</th>
					                        </tr>
					                    </thead>
					                </table>
                        		</div>
                        	</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Equipe de pose</label>
                                        <select name="equipe_pose" class="form-control">
                                        	@foreach($poseurs as $p)
                                            <option value="{{ $p->id }}">{{$p->name.' '.$p->lastname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6" >
                                    <div class="form-group datepicker">
                                        <label class="control-label">Date de pose</label>
                                        <input type="text" name="date_pose_finale" class="form-control datepicker1"> </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="checked_commandes_list" value="">
                        <div class="form-actions right">
                            <button type="submit" class="btn blue">
                                <i class="fa fa-check"></i> Planifier</button>
                        </div>
        			{{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after-scripts')
	<script src="https://cdn.datatables.net/select/1.2.1/js/dataTables.select.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
	<script>
		$(function() {
            $('.datepicker1').datetimepicker({
                format : 'dd-mm-yyyy hh:ii',
            });
			$('#commandes-table').DataTable({
        		// scrollY:  "200px",
				ordering: false,
	            processing: true,
                searching: false,
	            serverSide: true,
	            ajax: '{!! route('frontend.commandes.getAllCommandesForPose') !!}',
		        language: {
		            "sProcessing":     "Traitement en cours...",
				    "sSearch":         "Rechercher&nbsp;:",
				    "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
				    "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
				    "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ment",
				    "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				    "sInfoPostFix":    "",
				    "sLoadingRecords": "Chargement en cours...",
				    "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
				    "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
				    "oPaginate": {
				        "sFirst":      "Premier",
				        "sPrevious":   "Pr&eacute;c&eacute;dent",
				        "sNext":       "Suivant",
				        "sLast":       "Dernier"
				    },
				    "oAria": {
				        "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
				        "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
				    }
		        },
	            columns: [
	                { data: 'checkbox', name: 'checkbox' },
                    { data: 'commande', name: 'commande' },
                    { data: 'dates', name: 'dates' },
	                { data: 'actions', name: 'created_at' },
	            ]
	        }).on('draw.dt', function(){
	        	$('input[name="commandes_chk[]"]').trigger('change').change(function() {
                    if (this.checked) {
                        $(this).parent('span').addClass('active checked');
                    }else{
                        $(this).parent('span').removeClass('active checked');
                    }
                    var list = [];
	    		    $('input[name="commandes_chk[]"]:checked').each(function(i,x){
		                list.push(parseInt(x.value));
	        		});
	    		    $('input[name="checked_commandes_list"]').val(JSON.stringify(list));
                });
	        });

	   
	        // var planificationsModel = new Vue({
	        // 	el: '#planificationsModel',
	        // 	data: {
	        // 		checked_commandes: []
	        // 	}
	        // });

		});
	</script>
@endsection