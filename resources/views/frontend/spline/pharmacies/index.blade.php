@extends('frontend.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> Liste des pharmacies</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a href="{{route('frontend.pharmacies.create')}}" id="sample_editable_1_new" class="btn sbold btn-success"> Ajouter une pharmacie
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="pharmacies-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Secteur</th>
                            <th>Adresse</th>
                            <th>Téléphone</th>
                            <th></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
@endsection

@section('after-scripts')
<script src="/vendor/datatables/buttons.server-side.js"></script>

<script>
    $(function() {
        $('#pharmacies-table').DataTable({
            processing: true,
            serverSide: true,
            language: {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            },
            ajax: '{!! route('frontend.pharmacie.getAllPharmacies') !!}',
            order: [[4, 'desc']],
            columns: [
                { data: 'nom', name: 'nom' },
                { data: 'secteur', name: 'secteur' },
                { data: 'adresse', name: 'adresse' },
                { data: 'telephone', name: 'telephone' },
                { data: 'created_at', name: 'created_at', visible: false },
                { data: 'actions', name: 'actions' },
            ],
            columnDefs: [
                { width: 254, targets: 0 }
            ],
        });

        $('#pharmacies-table').on('init.dt draw.dt', function() {
            $('.delete-pharmacie').on('click', function() {
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
                        url: '/pharmacies/' + id,
                        type: 'DELETE',
                        success: function(result) {
                            var table = $('#pharmacies-table');
                            console.log(result);
                            if(result == "success"){
                                table.DataTable().draw();
                                swal("Opération terminée!", "La pharmacie a été supprimée avec succès!", "success");
                            }else{
                                swal("Opération non permise!", "La pharmacie ne peut pas être supprimée!", "warning");
                            }
                        }
                    });
                });
            });
        });
    });
</script>
@endsection
