<div class="col-md-12">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
			<i class="icon-plus font-blue-hoki"></i>
				<span class="caption-subject font-blue-hoki bold uppercase">Ajouter un nouveau produit</span>
			</div>
			<div class="tools">
			</div>
		</div>
		<div class="portlet-body form">
			<!-- BEGIN FORM-->
			<div class="form-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">Nom du produit</label>
							<input type="text" name="nom_produit" class="form-control"> </div>
						</div>
					</div>
					@role('Administrator')
					<div class="form-group">
						<label class="control-label">Liste des clients</label>
						<select name="client_id" class="form-control">
							@foreach($clients as $c)
							<option value="{{ $c->id }}">{{ $c->name }}</option>
							@endforeach
						</select>
					</div>
					@endauth
				</div>
			</div>
			<div class="form-actions right">
				<button type="button" class="btn default" data-dismiss="modal">Fermer</button>
				<button type="button" id="create-product-btn" class="btn blue" data-dismiss="modal">
					<i class="fa fa-check"></i> Ajouter</button>
				</div>
				
			</div>
		</div>
	</div>
</div>

<script>
	$('#create-product-btn').on('click', function() {
        $.ajax({
            url: '/products',
            type: 'POST',
            async: false,
            data: {
            	nom_produit : $('input[name="nom_produit"]').val(),
            	client_id : $('select[name="client_id"]').val()
            },
            success: function(result) {
                var table = $('#products-table');
                table.DataTable().draw();
            }
        });
    });
</script>