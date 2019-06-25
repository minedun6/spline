<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Modification d'un délégué</h4>
</div>
{{ Form::open(['route' => ['frontend.delegues.update', $delegue->id], 'method' => 'put']) }}
<div class="modal-body">
    <div class="row">
    	<div class="col-md-12">
    		<div class="form-body">
    			<div class="form-group">
                    <label>Nom & Prénom</label>
                    <input type="text" name="firstname" value="{{$delegue->firstname}}" class="form-control" placeholder=""> 
                </div>
               {{--  <div class="form-group">
                    <label>Prénom</label>
                    <input type="text" name="lastname" class="form-control" placeholder=""> 
                </div> --}}
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="phone" value="{{$delegue->phone}}" class="form-control" placeholder=""> 
                </div>
    		</div>
    	</div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-warning btn-outline">Mettre à jour</button>
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Fermer</button>
</div>
{{ Form::close() }}
