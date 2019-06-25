@extends('frontend.layouts.app')

@section('after-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> Liste des délégués</span>
                </div>
                <div class="actions">
                    <div class="btn-group">
                        <a class="btn btn-success btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Actions
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a data-toggle="modal" href="#basic-1"><i class="fa fa-plus"></i> Ajouter un délégué</a>
                            </li>
                            <li>
                                <a data-toggle="modal" href="{{route('frontend.delegues.assign')}}" data-target="#ajax"><i class="fa fa-arrow-right"></i> Affecter un délégué</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                @if ( session('err') and session('err') != '' )
                     {!! session('err') !!}
                @endif
               
                <table class="table table-striped table-bordered table-hover table-checkable order-column" id="delegues-table">
                    <thead>
                        <tr>
                            <th>Nom & Prénom</th>
                            <th>Téléphone</th>
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

<div class="modal fade" id="edit-delegue-modal" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Chargement... </span>
            </div>
        </div>
    </div>
</div>

@include('frontend.spline.delegues.partials.create')
@endsection

@section('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>

<script src="/vendor/datatables/buttons.server-side.js"></script>

<script>
    $(function() {
        $('#delegues-table').DataTable({
            processing: true,
            serverSide: true,
            language: {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
            },
            ajax: '{!! route('frontend.delegues.getAllDelegues') !!}',
            columns: [
                { data: 'firstname', name: 'firstname' },
                { data: 'phone', name: 'phone' },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'created_at' },
            ]
        });

        $('#delegues-table').on('init.dt draw.dt', function() {
            $('.delete-delegue').on('click', function() {
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
                        url: '/delegues/' + id,
                        type: 'DELETE',
                        success: function(result) {
                            var table = $('#delegues-table');
                            console.log(result);
                            if(result == "success"){
                                table.DataTable().draw();
                                swal("Opération terminée!", "Le délégué a été supprimé avec succès!", "success");
                            }else{
                                swal("Opération non permise!", "Le délégué ne peut pas être supprimé!", "warning");
                            }
                        }
                    });
                });
            });
        });
    });
</script>
@endsection
