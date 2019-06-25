@extends('frontend.layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> Liste des commandes</span>
                </div>
                <div class="actions">
                    @if(Auth::user()->hasRole('Collaborateur'))
                    <div class="btn-group">
                        <a href="{{route('frontend.commandes.create')}}" id="sample_editable_1_new" class="btn sbold btn-success"> Ajouter une commande
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="commandes-table">
                    <thead>
                        <tr>
                            <th width="100">Status</th>
                            <th>Type</th>
                            <th @if(is_client()) class="hidden"@endif>Client</th>
                            <th>Pharmacie</th>
                            <th>Date d'ajout</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th width="122">Actions</th>
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
        $('#commandes-table').DataTable({
            processing: true,
            serverSide: false,
            language: {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            },
            order: [[ 4, 'desc' ]],
            ajax: '{!! route('frontend.commandes.getAllCommandes') !!}',
            columns: [
                { data: 'status', name: 'status' },
                { data: 'type', name: 'type' },
                { data: 'client_name', name: 'client_name' @if(is_client()),visible: false @endif },
                { data: 'pharmacie', name: 'pharmacie' },
                { data: 'date_ajout', name: 'date_ajout' },
                { data: 'created_at', name: 'created_at', visible: false },
                { data: 'deleted_at', name: 'deleted_at', visible: false },
                { data: 'deleted_by', name: 'deleted_by', visible: false },
                { data: 'actions' },
            ],
            fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                if ( aData.deleted_at != null )
                {
                    $('td', nRow).first().find('span').popover();
                }
            }
        });


        $('#commandes-table').on('init.dt draw.dt', function() {
            $('.delete-commande').on('click', function() {
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
                        url: '/commandes/' + id,
                        type: 'DELETE',
                        success: function(result) {
                            var table = $('#commandes-table');
                            table.DataTable().draw();
                            if(result == 'success'){
                                swal("Opération terminée!", "La commande a été supprimée avec succès!", "success");
                                location.reload();
                            }else{
                                swal("Opération non permise!", "La commande ne peut être supprimée!", "warning");
                            }
                        }   
                    });
                });
            });
        });

    });
</script>
@endsection
