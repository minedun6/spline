@extends('frontend.layouts.app')
@section('after-styles')
<style>
    .cbp-l-caption-body span{
        height: 18px;
        width: 0 !important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">

@endsection
@section('content')
    <div class="row-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase"> Modifier une commande</span>
                </div>
                <div class="actions">
                    
                </div>
            </div>
            <div class="portlet-body form" >
               {{ Form::open(['route' => ['frontend.commandes.update', $editModel->commande->id], 'id' => 'commandeModel','class' => 'form-horizontal v-cloak', 'files' => true, '@submit' => 'submit', 'method' => 'put']) }}
                    <div class="form-body" v-cloak>
                        @role('Administrator')
                        <div class="form-group">
                            <label class="col-md-3 control-label">Client</label>
                            <div class="col-md-9">
                                <select class="form-control selectpicker" name="client_id" data-live-search="true">
                                @foreach($editModel->clients as $client)
                                    <option value="{{$client->id}}" @if ($editModel->client_id == $client->id)
                                        selected 
                                    @endif>{{$client->name}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        @endauth
                        <div class="form-group">
                            <label class="col-md-3 control-label">Type de la commande</label>
                            <div class="col-md-9 radio-list">
                                <label class="radio-inline">
                                  <input type="radio" name="commande_type" :checked="this == commande_type" v-model="commande_type" value="vitrine"> Vitrine
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="commande_type" :checked="this == commande_type" v-model="commande_type" value="presentoir"> Présentoir
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="commande_type" :checked="this == commande_type" v-model="commande_type" value="lineaire-merchandising"> Linéaire/ Merchandising
                                </label>
                                <label class="radio-inline">
                                  <input type="radio" name="commande_type" :checked="this == commande_type" v-model="commande_type" value="autre"> Autre
                                </label>       
                            </div>               
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Date souhaitée</label>
                            <div class="col-md-9">
                                <input class="form-control" v-validate v-datepicker="date_pose" name="date_pose" size="16" type="text">
                                <span class="help-block"> La date souhaitée n’est considérée comme définitive que sous réserve de confirmation de SPLINE. Des modifications concernant de votre date souhaitée peuvent être effectuées (veuillez consulter le plan de tournée) </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Description</label>
                            <div class="col-md-9">
                                <textarea class="form-control" v-validate v-model="description" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="form-group"  v-show="commande_type == 'vitrine'">
                            <label class="col-md-3 control-label">Produits</label>
                            <div class="col-md-9">
                                <select class="form-control selectpicker" v-model="product_id" name="product_id" data-live-search="true">
                                    <option class="bs-title-option" value="">Veuillez choisir un produit</option>
                                    @foreach($editModel->products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group" v-show="commande_type == 'vitrine'">
                            <label class="col-md-3 control-label">Type du support</label>
                            <div class="col-md-9">
                                <select class="form-control selectpicker" v-model="type_support" name="type_support" data-live-search="true">
                                    <option class="bs-title-option" value="">Veuillez choisir un type de support</option>
                                    <option>OPAQUE</option>
                                    <option>DECOUPE</option>
                                    <option>MICROPERFORE</option>
                                    <option>DOS GRIS</option>
                                    <option>BACK LIGHT</option>
                                    <option>PRINT PLEXIGLAS</option>
                                    <option>PRINT PVC</option>
                                    <option>PRINT MELAMINE</option>
                                    <option>PRINT FOAM BOARD</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Pharmacie
                            @if(Auth::user()->hasRole('Administrator'))
                            @elseif(Auth::user()->hasRole('Collaborateur'))
                            <a class="btn-lg btn-circle btn-icon-only blue" href="{{route('frontend.pharmacie.createPartial')}}" data-target="#ajax" data-toggle="modal" data-backdrop="false" >
                                    <i class="icon-plus"></i>
                                </a>
                            @endif
                            </label>
                            <div class="col-md-9">
                                <select class="form-control pharmacie_picker selectpicker" v-validate v-on:change="triggerSelect(pharmacie_id)" v-model="pharmacie_id" name="pharmacie_id" v-model="pharmacie_id" required="required" data-live-search="true">
                                    <option class="bs-title-option" value="">Veuillez choisir une pharmacie</option>
                                @foreach($editModel->pharmacies as $pharmacie)
                                    <option value="{{$pharmacie->id}}" data-content="{{$pharmacie->nom}} @if(!$pharmacie->is_active)<span class='label label-danger'>Non active</span>@endif"></option>
                                @endforeach
                                </select>
                            </div>
                        </div> 
                        <div class="form-group" v-show="pharmacie_vitrines_length > 0 ">
                            <label class="col-md-3 control-label">Photos</label>
                            <div class="col-md-9">
                                <div class="portfolio-content portfolio-1">
                                    <div class="pharmacie_vitrines hidden cbp">
                                        <div class="cbp-item graphic" v-for="img, index in pharmacie_vitrines">
                                            <div class="md-radio-inline">
                                                <div class="md-radio">
                                                    <input v-show="commande_type == 'vitrine'" type="radio" name="vitrines" v-model="choosen_vitrine_image" :value="img.vitrine" class="md-radiobtn" :id="'vitrine-'+img.vitrine" :checked="choosen_vitrine_image == img.vitrine">
                                                    <label :for="'vitrine-'+img.vitrine">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> Photo #@{{img.vitrine}} 
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="cbp-caption">
                                                <div class="cbp-caption-defaultWrap">
                                                    <img :src="'/'+img.path" alt=""> </div>
                                                <div class="cbp-caption-activeWrap">
                                                    <div class="cbp-l-caption-alignCenter">
                                                        <div class="cbp-l-caption-body">
                                                            <a :href="'/'+img.path" class="cbp-lightbox cbp-l-caption-buttonRight btn red uppercase btn red uppercase" :data-title="'Vitrine '+img.vitrine">Agrandir</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="cbp-l-grid-projects-title uppercase text-center uppercase text-center"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <div class="form-group" v-show="commande_type == 'vitrine' && pharmacie_vitrines_length > 0 ">
                            <label class="col-md-3 control-label">Vitrine</label>
                            <div class="col-md-9">
                                <div class="md-radio-inline">
                                    <div class="md-radio" v-for="i in nbre_vitrines">
                                        <input type="radio" :id="'radio'+i" name="choosen_vitrine" :value="i" class="md-radiobtn" v-model="choosen_vitrine">
                                        <label :for="'radio'+i">
                                            <span class="inc"></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Vitrine #@{{i}} 
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" v-show="pharmacie_id != 0 ">
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-9">
                                <div class="portlet light portlet-fit bordered">
                                    <div id="map" class="gmaps" style="position: relative; overflow: hidden;"></div>
                                </div>
                            </div>
                        </div>  

                        <div class="form-group">
                            <label class="col-md-3 control-label">Fichiers joints 
                                <a class="btn-lg btn-circle btn-icon-only blue" @click="addRow()">
                                    <i class="icon-plus"></i>
                                </a>
                            </label>
                            <div class="col-md-9">
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> Fichier </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="fichier, index in fichiers_joints">
                                            <td>@{{index+1}}</td>
                                            <td> 
                                                <input v-show="fichier.filled == 0" @change="addFile(fichier)" type="file" :name="'fichiers[]'"/>
                                                <input class="btn btn-warning" v-show="fichier.filled == 1" @click="removeFile(fichier)" type="button" value="Changer fichier">
                                            </td>
                                            <td><span @click="removeRow(fichier)"><i class="fa fa-remove" style="color: red;cursor: pointer;width:3%; max-width:3%!important;"></i> </span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" :disabled="disabled()" v-on:click="markAsDraft(false)" class="btn btn-success">Enregistrer</button>
                                <input type="hidden" name="brouillon" v-model="brouillon">
                            </div>
                        </div>
                    </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="modal bs-modal-lg fade" id="ajax" role="basic" aria-hidden="true">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?v=3.20&key=AIzaSyBQS5hST_G92pNFu5vOTFpdMDjfsO3J1pQ"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.js"></script>
    <script>
        $(function () {
            setTimeout(function() { 
                $(".selectpicker").selectpicker();
                $(".bootstrap-select").click(function () {
                     $(this).addClass("open");
                }); 
                $('[name="client_id"]').on('changed.bs.select', function () {
                    var id = $(this).find("option:selected").val();
                    _this = this;
                    $.ajax({
                        method: "GET",
                        url: '/products/getByClient',
                        data: {
                            'id' : id
                        }
                    }).done(function (data){
                        if(data.length == 0){
                            $('[name="product_id"]').empty()
                                    .append($("<option></option>")
                                    .attr("value", 0).text("Veuillez choisir un produit"))
                                    .selectpicker('refresh');
                        }else{
                            $('[name="product_id"]').empty();
                            data.forEach(function(d, i){
                                $('[name="product_id"]').append($("<option></option>")
                                    .attr("value", d.id).text(d.name));
                            });
    
                            $('[name="product_id"]').selectpicker('refresh');
                        }
                    });
                });
            }, 500);

            var pharmacieModel = new Vue({
                el: '#commandeModel',
                data: {
                    // choosen_vitrine_image: -1,
                    commande_type: "{!! $editModel->commande_type !!}",
                    pharmacie_id: {!! $editModel->pharmacie_id !!},
                    pharmacie_vitrines: {!! $editModel->pharmacie_vitrines !!},
                    fichiers_joints : [
                        {filled: 0},
                    ],
                    type_support: '{!! $editModel->commande->type_support !!}',
                    product_id: {!! $editModel->product_id !!},
                    description: '{!! $editModel->commande->description !!}',
                    date_pose: @if($editModel->commande->date_pose)'{!! $editModel->commande->date_pose->format("Y-m-d") !!}'@else '' @endif,
                    brouillon: false,
                    pharmacie_vitrines_length: 0,
                    choosen_vitrine: {!! json_decode($editModel->commande->extras)->{'choosen_vitrine'} !!},
                    nbre_vitrines: {{$editModel->pharmacie->nbre_vitrines}},
                    cubeportfolio: 0,
                    choosen_vitrine_image: {!! $editModel->choosen_vitrine_image !!},
                    map: ''
                },
                mounted: function() {
                    $("input[name='commande_type'][value='{!! $editModel->commande_type !!}']").parent('span').addClass('active checked');
                    $("input[name='commande_type']").trigger('change').change(function() {
                        if (this.checked) {
                            var this_id = $(this).val();
                            $("input[name='commande_type']").parent('span').removeClass('active checked');
                            $(this).parent('span').addClass('active checked');
                        }
                    });

                    var map = new GMaps({
                      div: '#map',
                      lat: 36.9189700,
                      lng: 8.1657900,
                      zoom: 7,
                      width: 550,
                      height: 400
                    });
                    this.map = map;

                    window.dispatchEvent(new Event('resize'));
                    this.triggerSelect(this.pharmacie_id, false);
                    
                },
                methods: {
                    triggerSelect: function (p, b = true) {
                        var vitrines;
                        _this = this;
                        $.ajax({
                            method: "GET",
                            url: '/pharmacies/getPharmacieVitrines/'+p,
                            beforeSend: function() {
                                // $('#invoice_submit').attr('disabled', '');
                            },
                            complete: function() {
                                
                            }
                        }).done(function(data) {
                            _this.nbre_vitrines = data[1].nbre_vitrines;
                            _this.pharmacie_vitrines_length = data[0].length;
                            if (b) {
                                _this.choosen_vitrine = 1;
                                _this.choosen_vitrine_image = 1;
                            }
                            if(data[0].length > 0){
                                _this.pharmacie_vitrines = [];
                                data[0].forEach(function(d, i){
                                    var img = { filled : false, path: d.path, vitrine: d.vitrine};
                                    _this.pharmacie_vitrines.push(img);
                                });
                            }else{
                                _this.choosen_vitrine = 0;
                            }
                            setTimeout(function(){
                                if(_this.pharmacie_vitrines.length > 0 && _this.cubeportfolio != 0){
                                    $('.pharmacie_vitrines').addClass('hidden');
                                    $('.pharmacie_vitrines').cubeportfolio('destroy');
                                    _this.cubeportfolio = 0;
                                }
                                if(_this.pharmacie_vitrines.length > 0 ){
                                    for (var i = 0; i < _this.map.markers.length; i++) {
                                        _this.map.markers[i].setMap(null);
                                    }
                                    _this.map.addMarker({
                                      lat: data[1].lat,
                                      lng: data[1].long
                                    });
                                    window.dispatchEvent(new Event('resize'));

                                    $('.pharmacie_vitrines').cubeportfolio({
                                       filters: '#js-filters-juicy-projects',
                                       width: false,
                                       loadMore: '#js-loadMore-juicy-projects',
                                       loadMoreAction: 'click',
                                       layoutMode: 'grid',
                                       defaultFilter: '*',
                                       animationType: 'quicksand',
                                       gapHorizontal: 35,
                                       gapVertical: 30,
                                       gridAdjustment: 'responsive',
                                       mediaQueries: [{
                                           width: 1500,
                                           cols: 5
                                       }, {
                                           width: 1100,
                                           cols: 4
                                       }, {
                                           width: 800,
                                           cols: 3
                                       }, {
                                           width: 480,
                                           cols: 2
                                       }, {
                                           width: 320,
                                           cols: 1
                                       }],
                                       caption: 'overlayBottomReveal',
                                       displayType: 'sequentially',
                                       displayTypeSpeed: 80,

                                       // lightbox
                                       lightboxDelegate: '.cbp-lightbox',
                                       lightboxGallery: true,
                                       lightboxTitleSrc: 'data-title',
                                       lightboxCounter: '<div class="cbp-popup-lightbox-counter">@{{current}} of @{{total}}</div>',

                                       // singlePage popup
                                       singlePageDelegate: '.cbp-singlePage',
                                       singlePageDeeplinking: true,
                                       singlePageStickyNavigation: true
                                   });
                                    _this.cubeportfolio = 1;
                                    $('.pharmacie_vitrines').removeClass('hidden');
                                }
                            }, 500);
                        });

                    },
                    addRow: function() {
                        this.fichiers_joints.push({filled:0});
                    },
                    removeRow: function(key) {
                        this.fichiers_joints.splice(key, 1);
                    },
                    addFile: function(fichier) {
                        fichier.filled = 1;
                    },
                    removeFile: function(fichier){
                        fichier.filled = 0;
                    },
                    refresh: function() {
                        this.$forceUpdate();
                    },
                    checkAllFields: function(e){
                        
                        if(this.commande_type == "vitrine" && this.choosen_vitrine_image == -1){
                            return false;
                        }

                        if(this.commande_type == "vitrine" && (this.type_support.length === 0 || this.product_id == 0)){
                            return false;
                        }

                        if(this.commande_type == "vitrine" && (this.pharmacie_vitrines_length != 0  && this.choosen_vitrine == 0 )){
                            return false;
                        }

                        return true;
                    },
                    disabled: function () {
                        return !this.checkAllFields();
                    },
                    markAsDraft: function (b){
                        this.brouillon = b;
                    },
                    submit: function (e) {
                        e.preventDefault();

                        if(this.brouillon || this.checkAllFields()){
                            document.getElementById('commandeModel').submit();
                        }

                        return false;
                    }
                },
            });

        });
    </script>

@endsection