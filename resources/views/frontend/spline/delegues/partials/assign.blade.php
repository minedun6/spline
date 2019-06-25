
{{ Form::open(['route' => 'frontend.delegues.assign.store', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
    	<div class="col-md-12">
    		<div class="form-body">
    			<div class="form-group">
                    <label>Délégués</label>
                    <select name="delegue_id" class="form-control selectpicker" title="Veuillez choisir un délégué" data-live-search="true">
                        @foreach($delegues as $d)
                        <option value="{{$d->id}}">{{$d->firstname.' '.$d->lastname.' - '.$d->phone}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Clients</label>
                    <select name="client_id" class="form-control selectpicker" title="Veuillez choisir un client" data-live-search="true">
                        @foreach($clients as $c)
                        <option value="{{$c->id}}">{{$c->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Pharmacies</label>
                    <select name="pharmacie_id" class="form-control selectpicker" title="Veuillez choisir une pharmacie" data-live-search="true">
                        @foreach($pharmacies as $p)
                        <option value="{{$p->id}}">{{$p->nom}}</option>
                        @endforeach
                    </select>
                </div>
    		</div>
    	</div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-success btn-outline">Enregistrer</button>
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Fermer</button>
</div>
{{ Form::close() }}

<script>
    $(".selectpicker").selectpicker({
        placeholder: 'ss'
    });
    $(".bootstrap-select").click(function () {
         $(this).addClass("open");
    });
</script>