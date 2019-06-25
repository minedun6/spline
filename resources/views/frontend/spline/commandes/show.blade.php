@extends('frontend.layouts.app')

@section('after-styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
@endsection
@section('content')
    <div class="row-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase"> Détail de la commande #{{$commande->id}}</span>  <small>- ajoutée par {{$commande->owner->name}} {{$commande->owner->lastname}}</small>
                </div>
                @if(!$commande->trashed())
                <div class="actions">

                    @if(Auth::user()->hasRole('Administrator'))
                    <div class="row">
                    <div class="btn-group">
                        <a class="btn green-haze btn-outline btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false"> Actions
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a @if(in_array($commande->status, ['en impression'])) class="text-left btn default disabled" @endif href="{{route('frontend.commandes.validateCommande', ['id' => $commande->id])}}"> Marquer comme conçue et validée</a>
                            </li>
                            <li>
                                <a @if(in_array($commande->status, ['en pose'])) class="text-left btn default disabled" @endif href="{{route('frontend.commandes.printCommande', ['id' => $commande->id])}}">  Marquer comme imprimée</a>
                            </li>
                            <li>
                                <a @if(in_array($commande->status, ['done'])) class="text-left btn green default disabled" @else class="btn green-jungle" @endif href="{{route('frontend.commandes.finishCommande', ['id' => $commande->id])}}">  Marquer comme terminée</a>
                            </li>
                            <li>
                                <a @if(in_array($commande->status, ['annulee'])) class="text-left btn red disabled" @else class="btn red" @endif href="{{route('frontend.commandes.cancelCommande', ['id' => $commande->id])}}">  Annuler la commande</a>
                            </li> 
                        </ul>
                    </div>
                    </div>
                    @else
                	@role('Crea')
                	<form @if(in_array($commande->status, ['en traitement'])) action="{{route('frontend.commandes.validateCommande', ['id' => $commande->id])}}" @endif>
                    	<button type="submit" class="btn btn-success" @if(!in_array($commande->status, ['en traitement'])) disabled @endif" > Marquer comme conçues et validées</button>
                    </form>
                    @endauth
                    @role('Print')
                    <form @if(in_array($commande->status, ['en impression'])) action="{{route('frontend.commandes.printCommande', ['id' => $commande->id])}}" @endif>
                        <button type="submit" class="btn btn-success" @if(!in_array($commande->status, ['en impression'])) disabled @endif" > Marquer comme imprimées</button>
                    </form>
                    @endauth
                    @endif
                </div>
                @endif
            </div>
            @if($commande->planification && (Auth::user()->hasRole('Collaborateur') || Auth::user()->hasRole('Poseur')))
            <div class="alert alert-info"><i class="icon icon-clock"></i> La date de pose est planifié pour le <b>{{$commande->planification->date_pose_finale->format('Y-m-d')}}</b></div>
            @endif
            <div class="mt-element-step">
                @if($commande->status == 'annulee')
                <div class="row step-no-background">
                    <div class="col-md-4 mt-step-col">
                    </div>
                    <div class="col-md-4 mt-step-col error">
                        <div class="mt-step-number bg-white font-grey"><i class="fa fa-close"></i></div>
                        <div class="mt-step-title uppercase font-grey-cascade">{{ json_decode($commande->extras)->{'history'}->{'canceled'} }}</div>
                        <div class="mt-step-content font-grey-cascade">La commande a été annulée</div>
                    </div>
                    <div class="col-md-4 mt-step-col">
                    </div>
                </div>
                @elseif($commande->status == 'done')
                <div class="row step-no-background">
                    <div class="col-md-4 mt-step-col">
                    </div>
                    <div class="col-md-4 mt-step-col done">
                        <div class="mt-step-number bg-white font-grey"><i class="fa fa-check"></i></div>
                        <div class="mt-step-title uppercase font-grey-cascade">{{ json_decode($commande->extras)->{'history'}->{'done'} }}</div>
                        <div class="mt-step-content font-grey-cascade">La commande est terminée</div>
                    </div>
                    <div class="col-md-4 mt-step-col">
                    </div>
                </div>
                @else
				<div class="row step-line">
                    <div class="col-md-3 mt-step-col first done">
                        <div class="mt-step-number bg-white">1</div>
                        <div class="mt-step-title uppercase font-grey-cascade">En traitement</div>
                        @if(isset(json_decode($commande->extras, true)['history']['created_at']))
                        <div class="mt-step-title font-grey-cascade"><b>{{ json_decode($commande->extras)->{'history'}->{'created_at'} }}</b></div>
                        @endif
                    </div>
                    {{-- en traitement / en validation / en impression / en pose / annulee / brouillon / done --}}
                    <div class="col-md-3 mt-step-col @if(in_array($commande->status, ['en impression', 'done', 'en pose'])) done @endif @if(in_array($commande->status, ['en traitement'])) error @endif">
                        <div class="mt-step-number bg-white">2</div>
                        <div class="mt-step-title uppercase font-grey-cascade">Créa/BAT</div>
                        @if(isset(json_decode($commande->extras, true)['history']['validated']) and in_array($commande->status, ['en impression', 'done', 'en pose']))
                        <div class="mt-step-title font-grey-cascade"><b>{{ json_decode($commande->extras)->{'history'}->{'validated'} }}</b></div>
                        @endif
                    </div>
                     <div class="col-md-3 mt-step-col @if(in_array($commande->status, ['done', 'en pose'])) done @endif @if(in_array($commande->status, ['en impression'])) error @endif">
                        <div class="mt-step-number bg-white">3</div>
                        <div class="mt-step-title uppercase font-grey-cascade">En impression</div>
                        @if(isset(json_decode($commande->extras, true)['history']['printed']) and in_array($commande->status, ['done', 'en pose']))
                        <div class="mt-step-title font-grey-cascade"><b>{{ json_decode($commande->extras)->{'history'}->{'printed'} }}</b></div>
                        @endif
                    </div>
                     <div class="col-md-3 mt-step-col  @if(in_array($commande->status, ['done'])) done @endif @if(in_array($commande->status, ['en pose'])) error @endif last">
                        <div class="mt-step-number bg-white">4</div>
                        <div class="mt-step-title uppercase font-grey-cascade">En pose</div>
                        @if(isset(json_decode($commande->extras, true)['history']['done']) and in_array($commande->status, ['done']))
                        <div class="mt-step-title font-grey-cascade"><b>{{ json_decode($commande->extras)->{'history'}->{'done'} }}</b></div>
                        @endif
                    </div>
                </div>
                @endif
		    </div>

            <div class="portlet-body form" >
            	<div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-user"></i> Informations sur la commande
                        </div>
                    </div>
                    @if(!is_client() and $commande->is_vitrine)
                    <div id="gallery" class="col-md-12" style="background-color: #fff">
                        @foreach($vitrines as $k => $vitrine)
                            <img data-src="{{ asset($vitrine->path)}}" alt="">
                        @endforeach
                    </div>
                    <table class="table table-bordered table-striped table-advance" style="margin: 0 !important">
                        <thead>
                            <tr>
                                <th><i class="fa fa-leaf"></i> Type</th>
                                <th><i class="fa fa-filter"></i> Nom du produit</th>
                                <th><i class="fa fa-map-pin"></i> Vitrine choisie</th>
                                <th><i class="fa fa-info"></i> Type du support</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    @if($commande->is_vitrine)
                                    Vitrine
                                    @elseif($commande->is_presentoir)
                                    Présentoir
                                    @elseif($commande->is_merchandising)
                                    Linéaire/ Merchandising
                                    @else
                                    Autres
                                    @endif
                                </td>
                                <td>{{$commande->product->name}}</td>
                                <td>
                                    @if(isset(json_decode($commande->extras, true)['choosen_vitrine']))
                                    <strong>{{json_decode($commande->extras)->{'choosen_vitrine'} }} </strong>
                                    @endif
                                </td>
                                <td @if(!is_admin() and Auth::user()->hasRole('Print')) style="font-size: 20px; font-weight: bold;" @endif>{{$commande->type_support}}{{!is_admin()}}</td>
                            </tr>
                        </tbody>
                    </table>
                    @endif

                    <div class="portlet-body">
                    	<div class="row">
                    		<div class="col-md-4">
                    			<dl>
                    				<dt>Nom du client</dt>
                    				<dd>{{$commande->client->name}}</dd>
                    			</dl>
                    			<dl>
                    				<dt>Logo du client</dt>
                    				<dd><img src="{{asset($commande->client->logo_path)}}" width="200" alt="Client logo"></dd>
                    			</dl>
                    		</div>
                    		<div class="col-md-4">
	                        	<dl>
		                        	<dt>Description</dt>
		                        	<dd>{{$commande->description}}</dd>
	                        	</dl>
	                        	<dl>
	                        		<dt>Type</dt>
	                        		<dd>@if($commande->is_vitrine)
										Vitrine
										@elseif($commande->is_presentoir)
										Présentoir
										@elseif($commande->is_merchandising)
										Linéaire/ Merchandising
										@else
										Autres
	                        		@endif</dd>
	                        	</dl>
	                        </div>
                            <div  class="col-md-4">
                        		<dl>
                                    <h5><strong>Détail de la pharmacie</strong></h5>
    	                        	<ul>
                                        <li><strong>Nom: </strong><a href="{{ route('frontend.pharmacies.show', $commande->pharmacie_id) }}">{{$commande->pharmacie->nom}}</a></li>
                                        <li><strong>Secteur: </strong>{{$commande->pharmacie->secteur}}</li>
                                        <li><strong>Téléphone: </strong>{{$commande->pharmacie->telephone}}</li>
                                        @if($delegue)
                                        <li><strong>Délégué: </strong>{{$delegue->firstname.' - '.$delegue->phone}}</li>
                                        @endif
                                        @if(!is_client() and isset(json_decode($commande->pharmacie->extras, true)['mesures']) and isset(json_decode($commande->pharmacie->extras, true)['mesures'][$choosen_vitrine]) )
                                        <li><strong>Mesures: </strong> L: {{json_decode($commande->pharmacie->extras, true)['mesures'][$choosen_vitrine]['width'] }}, H: {{json_decode($commande->pharmacie->extras, true)['mesures'][$choosen_vitrine]['height'] }}</li>
                                        @endif
                                    </ul>
    	                        </dl>
                            </div>
	                	</div>
                	</div>
                </div>
                <div class="portlet box blue">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-history"></i> Le journal
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="scroller" style="height: 339px; padding-left: 11px;" data-always-visible="1" data-rail-visible="0">
                                <ul class="feeds">
                                    @foreach($logs as $log)
                                    <li>
                                        <div class="col1">
                                            <div class="cont">
                                                <div class="cont-col1">
                                                    <div class="label label-sm 
                                                        @if($log->action == 'add')
                                                        label-success
                                                        @elseif($log->action == 'delete')
                                                        label-danger
                                                        @elseif($log->action == 'update')
                                                        label-warning
                                                        @elseif($log->action == 'planification')
                                                        label-info
                                                        @elseif($log->action == 'restore')
                                                        label-primary
                                                        @elseif($log->action == 'status')
                                                        label-warning
                                                        @endif
                                                    ">
                                                        @if($log->action == 'add')
                                                        <i class="fa fa-plus"></i>
                                                        @elseif($log->action == 'delete')
                                                        <i class="fa fa-trash"></i>
                                                        @elseif($log->action == 'update')
                                                        <i class="fa fa-pencil"></i>
                                                        @elseif($log->action == 'planification')
                                                        <i class="fa fa-clock-o"></i>
                                                        @elseif($log->action == 'restore')
                                                        <i class="fa fa-repeat"></i>
                                                        @elseif($log->action == 'status')
                                                        <i class="fa fa-bullhorn"></i>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="cont-col2">
                                                    <div class="desc"> {!!$log->content!!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col2" style="float: right; width: 90px">
                                                <div class="date"> {{$log->created_at->format('Y-m-d')}} </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            	<div class="portlet box green">
					<div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-tree"></i> La pharmacies
                        </div>
                    </div>
                    <div class="portlet-body">
                    	<div class="row">
                    		<div class="col-md-4">
                                <h5><strong>Détail de la pharmacie</strong></h5>
                                <ul>
                                    <li><strong>Nom: </strong><a href="{{ route('frontend.pharmacies.show', $commande->pharmacie_id) }}">{{$commande->pharmacie->nom}}</a></li>
                                    <li><strong>Secteur: </strong>{{$commande->pharmacie->secteur}}</li>
                                    <li><strong>Téléphone: </strong>{{$commande->pharmacie->telephone}}</li>
                                    @if($commande->is_vitrine)
                                    @if(isset(json_decode($commande->extras, true)['choosen_vitrine']))
                                    <li><strong>Vitrine choisie: </strong>{{json_decode($commande->extras)->{'choosen_vitrine'} }}</li>
                                    @endif
                                    @endif
                                </ul>
                            </div>
                            @if(isset(json_decode($commande->extras, true)['delegue']))
                            <div class="col-md-4">
                                <h5><strong>Délégué</strong></h5>
                                <ul>
                                    <li><strong>Nom: </strong>{{json_decode($commande->pharmacie->extras)->{'delegue'}->{'firstName'} }}</li>
                                    <li><strong>Prénom: </strong>{{json_decode($commande->pharmacie->extras)->{'delegue'}->{'lastName'} }}</li>
                                    <li><strong>Téléphone: </strong>{{json_decode($commande->pharmacie->extras)->{'delegue'}->{'phone'} }}</li>
                                </ul>
                            </div>
                            @endif
                    	</div>
                	</div>
				</div>
                @if(!is_client())
                <div class="portlet box yellow-lemon">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file"></i> Les piéces jointes
                        </div>
                        @role('Crea')
                        <div class="actions">
                            <div class="btn-group">
                                <a class="btn white btn-white btn-circle btn-sm" href="javascript:;" data-toggle="dropdown" data-hover="dropdown" data-close-others="true"> Actions
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a data-toggle="modal" href="#basic"> Ajouter une pièce jointe</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endauth
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            @if(count($files) != 0)
                            @role('Crea')
                            <div class="col-md-6 col-xs-12">
                                <h2>Piéces jointes du client</h2>
                                <div class="jstree_files tree-demo jstree jstree-1 jstree-default">
                                      <ul>
                                       <li data-jstree='{"opened":true}'> Fichiers
                                          <ul>
                                            @foreach($files as $index => $file)
                                            <li>
                                              <a href="/{{$file->path}}"> Fichier {{ $index + 1}}</a></li>
                                            @endforeach  
                                          </ul>
                                        </li>
                                      </ul>
                                </div>
                            </div>
                            @endauth
                            @endif
                            @if(count($crea_files) != 0)
                            @if(!is_client())
                            <div class="col-md-6 col-xs-12">
                                <h2>Piéces jointes équipe créa</h2>
                                <div class="jstree_files tree-demo jstree jstree-1 jstree-default">
                                      <ul>
                                       <li data-jstree='{"opened":true}'> Fichiers
                                          <ul>
                                            @foreach($crea_files as $index => $file)
                                            <li>
                                              <a href="/{{$file->path}}"> Fichier {{ $index + 1}}</a></li>
                                            @endforeach  
                                          </ul>
                                        </li>
                                      </ul>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div> 
                </div>
                <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Ajout d'une pièce jointe</h4>
                            </div>
                            <div class="modal-body">
                                <div class="col-md-3">
                                    Fichier
                                </div>
                                <div class="col-md-8">
                                    {{ Form::open(['route' => ['frontend.commandes.upload', $commande->id], 'files' => true]) }}
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="input-group input-large">
                                                <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                    <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                    <span class="fileinput-filename"> </span>
                                                </div>
                                                <span class="input-group-addon btn default btn-file">
                                                    <span class="fileinput-new"> Choisir un fichier </span>
                                                    <span class="fileinput-exists"> Changer </span>
                                                    <input type="file" name="fichiers[]"> </span>
                                                <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"> Supprimer </a>
                                            </div>
                                            <button type="submit" class="btn btn-success pull-right" style="margin-top: 10px"> Upload</button>
                                        </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                @endif
                @if($commande->planification && (Auth::user()->hasRole('Administrator') || ( $commande->planification->poseur_id == $logged_in_user->id || json_decode($commande->planification->extras)->{'done'})))
                <div class="portlet box red">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-calendar"></i>Les poses
                        </div>
                    </div>
                    <div class="portlet-body" v-cloak id="poseModel">
                        <div class="tabbable-custom nav-justified">
                            <ul class="nav nav-tabs nav-justified">
                                <li class="active">
                                    <a href="#tab_1_1_1" data-toggle="tab" aria-expanded="true"> Valider/ Réclamer </a>
                                </li>
                                @if($commande->status != 'done' && Auth::user()->hasRole('Administrator') || ( $commande->planification->poseur_id == $logged_in_user->id))
                                <li class="">
                                    <a href="#tab_1_1_2" data-toggle="tab" aria-expanded="false"> Reporter </a>
                                </li>
                                <li>
                                    <a href="#tab_1_1_3" data-toggle="tab" aria-expanded="false"> Annuler </a>
                                </li>
                                @endif
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_1_1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if($paths)
                                            <div class="portfolio-content portfolio-1">
                                                <div class="js-grid-juicy-projects" class="cbp">
                                                    @foreach($paths as $k => $path)
                                                    <div class="cbp-item graphic">
                                                        <div class="cbp-caption">
                                                            <div class="cbp-caption-defaultWrap">
                                                                <img src="{{ asset($path)}}" alt=""> </div>
                                                            <div class="cbp-caption-activeWrap">
                                                                <div class="cbp-l-caption-alignCenter">
                                                                    <div class="cbp-l-caption-body">
                                                                        <a href="{{ asset($path)}}" class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase">Agrandir</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="cbp-l-grid-projects-title uppercase text-center uppercase text-center">Images {{$k+1}}</div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if(Auth::user()->hasRole('Administrator') || (Auth::user()->hasRole('Poseur') && $logged_in_user->id == $commande->planification->poseur_id))
                                    {{ Form::open(['route' => ['frontend.commandes.poses.validate', $commande->id],'class' => 'form-horizontal', 'id' => 'poseModel', 'files' => true]) }}
                                    <div class="row">
                                        <label class="col-md-3 control-label">Photos 
                                            <a class="btn-lg btn-circle btn-icon-only blue" @click="addRow()">
                                                <i class="icon-plus"></i>
                                            </a>
                                        </label>
                                        <div class="col-md-9">
                                            <table class="table table-condensed table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th> Images </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="img, index in images">
                                                        <td>@{{index+1}}</td>
                                                        <td>
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="input-group input-large">
                                                                    <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                                        <span class="fileinput-filename"> </span>
                                                                    </div>
                                                                    <span class="input-group-addon btn default btn-file">
                                                                        <span class="fileinput-new"> Choisir un fichier </span>
                                                                        <span class="fileinput-exists"> Changer </span>
                                                                        <input type="file" @change="addFile(img)" :name="'photos['+index+']'" accept="image/*;capture=camera"> </span>
                                                                    <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput" @click="removeRow(img)"> Supprimer </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span @click="removeRow(img)"><i class="fa fa-remove" style="color: red;cursor: pointer;width:3%; max-width:3%!important;"></i> </span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-3 control-label">Réclamation 
                                        </label>
                                        <div class="col-md-9">
                                            <textarea name="reclamation" class="form-control" cols="30" rows="4">@if(isset(json_decode($commande->planification->extras, true)['reclamation'])){{json_decode($commande->planification->extras, true)['reclamation']['content']}}@endif</textarea>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="submit" :disabled="disabled()" class="btn green">Valider</button>
                                                <button type="submit" class="btn red" :disabled="disabled()">Reclamer & valider</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                    @endif
                                </div>
                                @if($commande->status != 'done' && (Auth::user()->hasRole('Administrator') || ( $commande->planification->poseur_id == $logged_in_user->id)))

                                <div class="tab-pane" id="tab_1_1_2">
                                    {{ Form::open(['route' => ['frontend.commandes.poses.reporter', $commande->id],'class' => 'form-horizontal', 'id' => 'poseModel', 'files' => true]) }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if($report_paths)
                                            <div id="gallery-1" style="margin-bottom: 23px;">
                                                @foreach($report_paths as $k => $path)
                                                    <img data-src="{{ asset($path)}}" alt=""> 
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-3 control-label">Date de report 
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" name="date_report" class="form-control datepicker1">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="col-md-3 control-label">Photos 
                                            <a class="btn-lg btn-circle btn-icon-only blue" @click="addRow('report')">
                                                <i class="icon-plus"></i>
                                            </a>
                                        </label>
                                        <div class="col-md-9">
                                            <table class="table table-condensed table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th> Images </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="img, index in images_report">
                                                        <td>@{{index+1}}</td>
                                                        <td>
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="input-group input-large">
                                                                    <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                                        <span class="fileinput-filename"> </span>
                                                                    </div>
                                                                    <span class="input-group-addon btn default btn-file">
                                                                        <span class="fileinput-new"> Choisir un fichier </span>
                                                                        <span class="fileinput-exists"> Changer </span>
                                                                        <input type="file" @change="addFile(img)" :name="'photos['+index+']'" accept="image/*;capture=camera"> </span>
                                                                    <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput" @click="removeRow(img, 'report')"> Supprimer </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span @click="removeRow(img, 'report')"><i class="fa fa-remove" style="color: red;cursor: pointer;width:3%; max-width:3%!important;"></i> </span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-3 control-label"> Notes 
                                        </label>
                                        <div class="col-md-9">
                                            <textarea name="report" class="form-control" cols="30" rows="4">@if(isset(json_decode($commande->planification->extras, true)['report'])){{json_decode($commande->planification->extras, true)['report']['content']}}@endif</textarea>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="submit" class="btn green" :disabled="checkFiles('report')">Valider</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                                <div class="tab-pane" id="tab_1_1_3">
                                    {{ Form::open(['route' => ['frontend.commandes.poses.refuser', $commande->id],'class' => 'form-horizontal', 'id' => 'poseModel', 'files' => true]) }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if($refus_paths)
                                            <div id="gallery-2" style="margin-bottom: 23px;">
                                                @foreach($refus_paths as $k => $path)
                                                    <img data-src="{{ asset($path)}}" alt=""> 
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-3 control-label">Photos 
                                            <a class="btn-lg btn-circle btn-icon-only blue" @click="addRow('refus')">
                                                <i class="icon-plus"></i>
                                            </a>
                                        </label>
                                        <div class="col-md-9">
                                            <table class="table table-condensed table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th> Images </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="img, index in images_refus">
                                                        <td>@{{index+1}}</td>
                                                        <td>
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="input-group input-large">
                                                                    <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                                        <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                                        <span class="fileinput-filename"> </span>
                                                                    </div>
                                                                    <span class="input-group-addon btn default btn-file">
                                                                        <span class="fileinput-new"> Choisir un fichier </span>
                                                                        <span class="fileinput-exists"> Changer </span>
                                                                        <input type="file" @change="addFile(img)" :name="'photos['+index+']'" accept="image/*;capture=camera"> </span>
                                                                    <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput" @click="removeRow(img, 'refus')"> Supprimer </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span @click="removeRow(img, 'refus')"><i class="fa fa-remove" style="color: red;cursor: pointer;width:3%; max-width:3%!important;"></i> </span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-3 control-label"> Notes 
                                        </label>
                                        <div class="col-md-9">
                                            <textarea name="refus" class="form-control" cols="30" rows="4">@if(isset(json_decode($commande->planification->extras, true)['refus'])){{json_decode($commande->planification->extras, true)['refus']['content']}}@endif</textarea>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="submit" class="btn green" :disabled="checkFiles('refus')">Valider</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                </div>
                @endif
			</div>
		</div>
	</div>
@endsection

@section('after-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>

<script>
   $(function () {
        $('.jstree_files').jstree().bind("select_node.jstree", function (e, data) {
             var href = data.node.a_attr.href;
             window.open(href);
        });
        $('.jstree_files').on("changed.jstree", function (e, data) {
          console.log(data.selected);
        });
        var g = $('#gallery').portfolio({
            showArrows: false,
            logger: false
        });
        g.init();
        
        if(document.getElementById("poseModel")){
            setTimeout(function(){
                $('.datepicker1').datetimepicker({
                    format : 'dd-mm-yyyy hh:ii',
                });
            },100);
            var poseModel = new Vue({
                el: '#poseModel',
                data: {
                    images_length: 1,
                    images: [
                        {filled: 0},
                    ],
                    images_refus: [
                        {filled: 0},
                    ],
                    images_report: [
                        {filled: 0},
                    ]
                },
                mounted: function() {
                    @if($report_paths)
                    var p = $("#gallery-1").portfolio();
                    p.init();
                    @endif
                    @if($refus_paths)
                    var p1 = $("#gallery-2").portfolio();
                    p1.init();
                    @endif
                },
                methods: {
                    addRow: function(type=null) {
                        if(type == 'report'){
                            this.images_report.push({filled: 0});
                        }else if(type == 'refus'){
                            this.images_refus.push({filled: 0});
                        }else{
                            this.images.push({filled: 0});
                        }
                    },
                    removeRow: function(key, type = null) {
                        if(type == 'report'){
                            this.images_report.splice(key, 1);
                        }else if(type == 'refus'){
                            this.images_refus.splice(key, 1);
                        }else{
                            this.images.splice(key, 1);
                        }
                    },
                    addFile: function(img) {
                        img.filled = 1;
                    },
                    removeFile: function(img){
                        img.filled = 0;
                    },
                    checkAllFiles: function(e){
                        var all_required_files_are_uploaded = true;
                        this.images.forEach(function(d, i){
                            if(d.filled == 0){
                                all_required_files_are_uploaded = false;
                            }
                        });

                        if(all_required_files_are_uploaded)
                            return true;
                        return false;
                    },
                    disabled: function(){
                        return !this.checkAllFiles() || this.images.length == 0;
                    },
                    checkFiles: function(type) {
                        var ok = true;
                        if(type == 'report'){
                            images = this.images_report;
                        }else if(type == 'refus'){
                            images = this.images_refus;
                        }else{
                            images = [];
                        }
                        images.forEach(function(d, i){
                            if(d.filled == 0){
                                ok = false;
                            }
                        });
                        return !ok || images.length == 0;
                    }
                },
            });
        }
    });
</script>

@endsection