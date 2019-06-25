<div class="modal fade" id="basic-1" tabindex="-1" role="basic-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Ajout d'un délégué</h4>
            </div>
           {{ Form::open(['route' => 'frontend.delegues.store']) }}
            <div class="modal-body">
                <div class="row">
                	<div class="col-md-12">
                		<div class="form-body">
                			<div class="form-group">
                                <label>Nom & Prénom</label>
                                <input type="text" name="firstname" class="form-control" placeholder=""> 
                            </div>
                           {{--  <div class="form-group">
                                <label>Prénom</label>
                                <input type="text" name="lastname" class="form-control" placeholder=""> 
                            </div> --}}
                            <div class="form-group">
                                <label>Téléphone</label>
                                <input type="text" name="phone" class="form-control" placeholder=""> 
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
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>