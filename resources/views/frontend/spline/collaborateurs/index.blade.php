@extends ('frontend.layouts.app')

@section ('title', trans('labels.backend.access.users.management'))

@section('content')
   <div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject bold uppercase"> Liste des collaborateurs</span>
                </div>
                <div class="actions">
                    <a href="{{route('frontend.collaborateurs.create')}}" id="sample_editable_1_new" class="btn sbold btn-success"> Ajouter nouveau collaborateur
                            <i class="fa fa-plus"></i>
                        </a>
                </div>
            </div>
            <div class="portlet-body">
                <table id="users-table" class="table table-striped table-bordered table-hover table-checkable">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.access.users.table.name') }}</th>
                            <th>{{ trans('labels.backend.access.users.table.email') }}</th>
                            <th>{{ trans('labels.backend.access.users.table.confirmed') }}</th>
                            <th>{{ trans('labels.backend.access.users.table.created') }}</th>
                            <th>{{ trans('labels.backend.access.users.table.last_updated') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
</div>
@stop

@section('after-scripts')

    <script>
        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: false,
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
                },
                ajax: {
                    url: '{{ route("frontend.collaborateurs.get") }}',
                    type: 'get',
                    data: {status: 1, trashed: false}
                },
                columns: [
                    {data: 'name', name: '{{config('access.users_table')}}.name', render: $.fn.dataTable.render.text()},
                    {data: 'email', name: '{{config('access.users_table')}}.email', render: $.fn.dataTable.render.text()},
                    {data: 'confirmed', name: '{{config('access.users_table')}}.confirmed'},
                    {data: 'created_at', name: '{{config('access.users_table')}}.created_at'},
                    {data: 'updated_at', name: '{{config('access.users_table')}}.updated_at'}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop
