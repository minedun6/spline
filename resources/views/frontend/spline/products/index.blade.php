@extends('frontend.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> Liste des Produits</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a class=" btn btn-success" href="{{route('frontend.products.create')}}" data-target="#ajax" data-toggle="modal"><i class="fa fa-plus"></i> Ajouter nouveau produit</a>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="products-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th @role('Collaborateur') style="width: 0 !important;" @endauth>Client</th>
                            <th>Ajouté le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
<div class="modal fade" id="ajax" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Chargement... </span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after-scripts')
<script src="/vendor/datatables/buttons.server-side.js"></script>

<script>
    $(function() {
        $('#products-table').DataTable({
            processing: true,
            serverSide: true,
            language: {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            },
            ajax: '{!! route('frontend.products.getAllProducts') !!}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'client', name: 'client', visible : <?php echo (Auth::user()->hasRole('Administrator')) ? 'true' : 'false' ?> },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'created_at' },
            ]
        });
        $('#products-table').on('init.dt draw.dt', function() {
            $('.delete-product').on('click', function() {
                var id = $(this).attr('data-id');
                swal({
                    title: "Êtes-vous sûr?",
                    text: "Vous ne pourrez pas récupérer cette commande!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Supprimer",
                    cancelButtonText: "Annuler",
                    closeOnConfirm: false
                }, function (isConfirm) {
                    if (!isConfirm) return;
                    $.ajax({
                        url: '/products/' + id,
                        type: 'DELETE',
                        success: function(result) {
                            var table = $('#products-table');
                            console.log(result);
                            if(result == "success"){
                                table.DataTable().draw();
                                swal("Opération terminée!", "Le produit a été supprimé avec succès!", "success");
                            }else{
                                swal("Opération non permise!", "Le produit ne peut pas être supprimé!", "warning");
                            }
                        }
                    });
                });
            });
        });
    });
</script>
@endsection
