@extends('frontend.layouts.app')

@section('content') 
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-home"></i> {{ trans('navs.general.home') }}
                </div>

                <div class="panel-body">
                    {{ trans('strings.frontend.welcome_to', ['place' => app_name()]) }}
                </div>
            </div><!-- panel -->

        </div><!-- col-md-10 -->


    </div><!--row-->
    @if(Auth::user()->hasRole('Collaborateur') && Auth::user()->collaborateur)
    <div class="row">
        <div class="col-md-7 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calendar"></i> 10 dernières commandes posées
                </div>

                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th> Date de pose </th>
                                <th> Pharmacie </th>
                                <th> Produit </th>
                                <th> Support </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($commandes as $c)
                            <tr>
                                <td> @if($c->planification_id) 
                                        {{$c->planification->date_pose_finale->format('Y-m-d')}} 
                                    @else
                                    Non disponible
                                    @endif
                                </td>
                                <td> {{$c->pharmacie->nom}} </td>
                                <td>@if($c->product) {{$c->product->name}} @else Non applicable @endif</td>
                                <td>@if($c->type_support) {{$c->type_support}} @else Non applicable @endif</td>
                                <td> <a href="/commandes/{{$c->id}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a> </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!-- panel -->
        </div>
    </div>
    @endif

   @if($images_pose)
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-picture-o"></i> 10 derniéres photos de pose
                </div>

                <div class="panel-body">
                    <div id="gallery-1">
                        @foreach($images_pose as $v)
                            @foreach(json_decode($v->path) as $image)
                                <img data-src="{{$image}}">
                            @endforeach
                        @endforeach
                    </div>
                </div>
        </div>
    </div>  
   @endif
@endsection

@section('after-scripts')
    <script>
        var g1 = $('#gallery-1').portfolio();
        g1.init({height: '350px'});
    </script>
@endsection