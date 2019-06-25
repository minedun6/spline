@extends('frontend.layouts.app')

@section('content')
    <div class="row-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase"> Mes poses</span>
                </div>
                <div class="actions">
                    
                </div>
            </div>
            <div class="portlet-body form" >
            <div class="mt-element-list">
                <div class="mt-list-container list-simple ext-1 group">
                    <a class="list-toggle-container" data-toggle="collapse" href="#completed-simple" aria-expanded="false">
                        <div class="list-toggle done uppercase"> Validées
                            <span class="badge badge-default pull-right bg-white font-green bold">{{count($mes_poses['completed'])}}</span>
                        </div>
                    </a>
                    <div class="panel-collapse collapse in" id="completed-simple">
                        <ul>
                            @foreach($mes_poses['completed'] as $p)
                            <li class="mt-list-item done">
                                <div class="list-icon-container">
                                    <i class="icon-check"></i>
                                </div>
                                <div class="list-datetime" style="width: 161px;"> {{ Carbon\Carbon::parse($p->date_pose_finale)->format('Y-m-d') }} </div>
                                <div class="list-item-content">
                                    <h3 class="uppercase">
                                        <a href="{{ route('frontend.commandes.show', ['id' => $p->commande_id])}}">@if($p->commande->is_vitrine)[Vitrine]@elseif($p->commande->is_merchandising)[Linéaire/ Merchandising] @elseif($p->commande->is_presentoire)[Présentoire] @else [Autre]@endif {{ $p->commande->client->name.(($p->commande->product) ? '/ '.$p->commande->product->name : '').'/ '.$p->commande->pharmacie->secteur.'/ '.$p->commande->pharmacie->nom }}</a>
                                    </h3>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <a class="list-toggle-container" data-toggle="collapse" href="#pending-simple" aria-expanded="true">
                        <div class="list-toggle uppercase"> En attente
                            <span class="badge badge-default pull-right bg-white font-dark bold">{{count($mes_poses['pending'])}}</span>
                        </div>
                    </a>
                    <div class="panel-collapse collapse in" id="pending-simple" aria-expanded="true">
                        <ul>
                        	@foreach($mes_poses['pending'] as $p)
                            <li class="mt-list-item">
                                <div class="list-icon-container">
                                    <i class="icon-close"></i>
                                </div>
                                <div class="list-datetime" style="width: 161px;"> {{ Carbon\Carbon::parse($p->date_pose_finale)->format('Y-m-d') }} </div>
                                <div class="list-item-content">
                                    <h3 class="uppercase">
                                        <a href="{{ route('frontend.commandes.show', ['id' => $p->commande_id])}}">@if($p->commande->is_vitrine)[Vitrine]@elseif($p->commande->is_merchandising)[Linéaire/ Merchandising] @elseif($p->commande->is_presentoire)[Présentoire] @else [Autre]@endif {{ $p->commande->client->name.(($p->commande->product) ? '/ '.$p->commande->product->name : '').'/ '.$p->commande->pharmacie->secteur.'/ '.$p->commande->pharmacie->nom }}</a>
                                    </h3>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        	</div>
    	</div>
	</div>
@endsection