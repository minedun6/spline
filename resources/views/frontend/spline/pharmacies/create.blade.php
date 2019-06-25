@extends('frontend.layouts.app')
@section('after-styles')
<style>
.form-horizontal .form-group {
    margin-right: 0;
    margin-left: 0;
}
</style>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
@endsection
@section('content')

    <div class="row-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase"> Formulaire d'ajout d'une pharmacie</span>
                </div>
                <div class="actions">
                    
                </div>
            </div>
            <div class="portlet-body form">
               {{ Form::open(['route' => 'frontend.pharmacies.store', 'id' => 'pharmacieModel','class' => 'form-horizontal', 'files' => true, '@submit' => 'checkAllFiles']) }}
                    <div class="form-body" v-cloak>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nom</label>
                            <div class="col-md-9">
                                <input type="text" name="nom" required="true" class="form-control" placeholder="" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Secteur</label>
                            <div class="col-md-9">
                                <select class="form-control" name="secteur"  data-live-search="true">
                                    <option>TUNIS 1-CENTRE VILLE</option>
                                    <option>TUNIS 1-LA FAYETTE</option>
                                    <option>TUNIS 1-BEB KHADRA</option>
                                    <option>TUNIS 1-BEB BNET</option>
                                    <option>TUNIS 1-MONPLAISIR</option>
                                    <option>TUNIS 1-OMRANE</option>
                                    <option>TUNIS 1-BEB SAADOUN</option>
                                    <option>TUNIS 1-AUTRE</option>
                                    <option>TUNIS 2-MARSA</option>
                                    <option>TUNIS 2-CARTHAGE</option>
                                    <option>TUNIS 2-LAC</option>
                                    <option>TUNIS 2-GAMMARTH</option>
                                    <option>TUNIS 2-AIN ZAGHOUAN</option>
                                    <option>TUNIS 2-KRAM</option>
                                    <option>TUNIS 2-LA GOULETTE</option>
                                    <option>TUNIS 2-AUTRE</option>
                                    <option>TUNIS 3-ARIANA</option>
                                    <option>TUNIS 3-SOUKRA</option>
                                    <option>TUNIS 3-BORJ LOUZIR</option>
                                    <option>TUNIS 3-RAOUED</option>
                                    <option>TUNIS 3-GHAZELA</option>
                                    <option>TUNIS 3-DIAR SOUKRA</option>
                                    <option>TUNIS 3-AUTRE</option>
                                    <option>TUNIS 4-MANAR</option>
                                    <option>TUNIS 4-MENZAH 1_4 </option>
                                    <option>TUNIS 4-MENZAH 5-9</option>
                                    <option>TUNIS 4-ENNASR</option>
                                    <option>TUNIS 4-CITE KHADHRA</option>
                                    <option>TUNIS 4-MUTUELLE VILLE</option>
                                    <option>TUNIS 4-CITE MAHRAJEN</option>
                                    <option>TUNIS 4-IBN KHALDOUN</option>
                                    <option>TUNIS 4-OMRANE SUP</option>
                                    <option>TUNIS 4-JARDINS D'EL MENZAH</option>
                                    <option>TUNIS 4-AUTRE</option>
                                    <option>TUNIS 5-BEN AROUS</option>
                                    <option>TUNIS 5-MEGRINE</option>
                                    <option>TUNIS 5-MOUROUJ</option>
                                    <option>TUNIS 5-RADES</option>
                                    <option>TUNIS 5-EZZAHRA</option>
                                    <option>TUNIS 5-BORJ CEDRIA</option>
                                    <option>TUNIS 5-HAMMAM CHOT</option>
                                    <option>TUNIS 5-MORNEG</option>
                                    <option>TUNIS 5-FOUCHENA</option>
                                    <option>TUNIS 5-MHAMDIA</option>
                                    <option>TUNIS 5-AUTRE</option>
                                    <option>TUNIS 6-BARDO</option>
                                    <option>TUNIS 6-ETADHAMEN</option>
                                    <option>TUNIS 6-MNIHLA</option>
                                    <option>TUNIS 6-CITE ZOUHOUR</option>
                                    <option>TUNIS 6-ZAHROUNI</option>
                                    <option>TUNIS 6-OMRANE SUP</option>
                                    <option>TUNIS 6-SIDI HASSINE</option>
                                    <option>TUNIS 6-DOUAR HICHER</option>
                                    <option>TUNIS 6-MANOUBA</option>
                                    <option>TUNIS 6-JDAIDA</option>
                                    <option>TUNIS 6-TEBOURBA</option>
                                    <option>TUNIS 6-DENDEN</option>
                                    <option>TUNIS 6-AGBA</option>
                                    <option>TUNIS 6-AUTRE</option>
                                    <option>BIZERTE-BIZERTE</option>
                                    <option>BIZERTE-M.BOURGUIBA</option>
                                    <option>BIZERTE-MATEUR</option>
                                    <option>BIZERTE-RAS JBEL</option>
                                    <option>BIZERTE-AUTRE</option>
                                    <option>CAP BON-NABEUL</option>
                                    <option>CAP BON-DAR CHAABANE</option>
                                    <option>CAP BON-MREZGA</option>
                                    <option>CAP BON-BENI KHIAR</option>
                                    <option>CAP BON-HAMMAMET</option>
                                    <option>CAP BON-BOUFICHA</option>
                                    <option>CAP BON-M.BOUZELFA</option>
                                    <option>CAP BON-HAOUARIA</option>
                                    <option>CAP BON-KELIBIA</option>
                                    <option>CAP BON-M.TEMIM</option>
                                    <option>CAP BON-ENCHAA</option>
                                    <option>CAP BON-KORBA</option>
                                    <option>CAP BON-MAAMOURA</option>
                                    <option>CAP BON-MIDA</option>
                                    <option>CAP BON-AUTRE</option>
                                    <option>NORD OUEST-BEJA</option>
                                    <option>NORD OUEST-JENDOUBA</option>
                                    <option>NORD OUEST-KEF</option>
                                    <option>NORD OUEST-BOUSSELEM</option>
                                    <option>NORD OUEST-MJEZ EL BEB</option>
                                    <option>NORD OUEST-SELIANA</option>
                                    <option>NORD OUEST-AUTRE</option>
                                    <option>SAHEL-MOKNINE</option>
                                    <option>SAHEL-JAMMEL</option>
                                    <option>SAHEL-BENNENE</option>
                                    <option>SAHEL-ZAOUIET SOUSSE</option>
                                    <option>SAHEL-SOUSSE</option>
                                    <option>SAHEL-MONASTIR</option>
                                    <option>SAHEL-MAHDIA</option>
                                    <option>SAHEL-KSAR HELAL</option>
                                    <option>SAHEL-KALAA KBIRA</option>
                                    <option>SAHEL-KALAA SGHIRA</option>
                                    <option>SAHEL-SAYEDA</option>
                                    <option>SAHEL-EL JEM</option>
                                    <option>SAHEL-SIDI EL HENI</option>
                                    <option>SAHEL-AUTRE</option>
                                    <option>CENTRE-KAIROUAN</option>
                                    <option>CENTRE-KASSERINE</option>
                                    <option>CENTRE-SBITLA</option>
                                    <option>CENTRE-GAFSA</option>
                                    <option>CENTRE-EL KTAR</option>
                                    <option>CENTRE-SIDI BOUZID</option>
                                    <option>CENTRE-AUTRE</option>
                                    <option>SUD-GABES</option>
                                    <option>SUD-HAMMA GABES</option>
                                    <option>SUD-MEDNINE</option>
                                    <option>SUD-DJERBA MIDOUN</option>
                                    <option>SUD-DJERBA H.SOUK</option>
                                    <option>SUD-DJERBA AJIM</option>
                                    <option>SUD-ZARZIS</option>
                                    <option>SUD-TATAOUINE</option>
                                    <option>SUD-NAFTA</option>
                                    <option>SUD-SFAX</option>
                                    <option>SUD-JBENIANA</option>
                                    <option>SUD-M.HBIB</option>
                                    <option>SUD-GHANOUCH</option>
                                    <option>SUD-BEN GARDEN</option>
                                    <option>SUD-BIR LAHMER</option>
                                    <option>SUD-MARETH</option>
                                    <option>SUD-SFAX</option>
                                    <option>SUD-AUTRE</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Adresse</label>
                            <div class="col-md-9">
                                <input type="text" name="adresse" required="true" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Téléphone</label>
                            <div class="col-md-9">
                                <input type="text" name="telephone" pattern="[0-9]{8}" required="true" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nombre de vitrines</label>
                            <div class="col-md-9">
                                <input type="number" required="true" min="1" v-model="nbre_vitrines" name="nbre_vitrines" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Mesures des vitrines</label>
                            <div class="col-md-9">
                                <div class="form-inline" v-for='i in nbre_vitrines'>
                                    <div class="form-group">
                                        <input type="number" min="0" required="true" :name="'mesures['+i+'][width]'" class="form-control input-xsmall mesures"> largeur <i class="fa fa-close"></i> <input type="number" min="0" required="true" :name="'mesures['+i+'][height]'" class="form-control input-xsmall mesures"> hauteur
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Photos 
                                <span class="btn-lg btn-circle btn-icon-only font-blue-steel" @click="addRow()">
                                    <i class="fa fa-plus-square-o"></i>
                                </span>
                            </label>
                            <div class="col-md-9">
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th> Images avec mesures </th>
                                            <th> Images sans mesures </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="img, index in images">
                                            <td> 
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="input-group input-large">
                                                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                            <span class="fileinput-filename"> </span>
                                                        </div>
                                                        <span class="input-group-addon btn default btn-file">
                                                            <span class="fileinput-new"> Choisir un fichier </span>
                                                            <span class="fileinput-exists"><i class="fa fa-refresh"></i></span>
                                                            <input type="file" @change="addFile(img[0])" :name="'photos['+index+'][0]'"> </span>
                                                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"><i class="fa fa-minus"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td> 
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="input-group input-large">
                                                        <div class="form-control uneditable-input input-fixed input-medium" data-trigger="fileinput">
                                                            <i class="fa fa-file fileinput-exists"></i>&nbsp;
                                                            <span class="fileinput-filename"> </span>
                                                        </div>
                                                        <span class="input-group-addon btn default btn-file">
                                                            <span class="fileinput-new"> Choisir un fichier </span>
                                                            <span class="fileinput-exists"><i class="fa fa-refresh"></i></span>
                                                            <input type="file" @change="addFile(img[1])" :name="'photos['+index+'][1]'"> </span>
                                                        <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput"><i class="fa fa-minus"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span @click="removeRow(img)"><i class="fa fa-remove" style="color: red;cursor: pointer;width:3%; max-width:3%!important;"></i> </span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Note</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="note" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">Latitude</label>
                            <div class="col-md-9">
                                <div class="portlet light portlet-fit bordered">
                                    <div id="map" class="gmaps" style="position: relative; overflow: hidden;"></div>
                                </div>
                                <input type="hidden" required="true" name="long" id="longitude" class="form-control"  placeholder="">
                                <input type="hidden" required="true" name="lat" id="latitude" class="form-control" placeholder="">
                            </div>
                        </div>
                    </div>
                     
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn btn-success">Enregistrer</button>
                            </div>
                        </div>
                    </div>
            
            {{ Form::close() }}
            </div>
        </div>
    </div>
   
@endsection

@section('after-scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>

    <script src="https://maps.googleapis.com/maps/api/js?v=3.20&key=AIzaSyBQS5hST_G92pNFu5vOTFpdMDjfsO3J1pQ"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.js"></script>

    <script>
        
        $(function () {
            
            setTimeout(function() { 
                $("select").selectpicker();
                $(".bootstrap-select").click(function () {
                     $(this).addClass("open");
                }); 
            }, 500);
            var pharmacieModel = new Vue({
                el: '#pharmacieModel',
                data: {
                    images_length: 1,
                    nbre_vitrines: 1,
                    images: [
                        [
                            {filled: '0'},
                            {filled: '0'}
                        ]
                    ]
                },
                mounted: function() {
                    var map = new GMaps({
                      div: '#map',
                      lat: 36.9189700,
                      lng: 10.1657900,
                      zoom: 8,
                      width: 550,
                      height: 400
                    });

                    GMaps.on('click', map.map, function(event) {
                        var lat = event.latLng.lat();
                        var lng = event.latLng.lng();
                          for (var i = 0; i < map.markers.length; i++) {
                            map.markers[i].setMap(null);
                          }
                        map.addMarker({
                          lat: lat,
                          lng: lng
                        });

                        $('#longitude').val(lng);
                        $('#latitude').val(lat);
                      });
                    $('.modal').on('shown.bs.modal', function (e) {
                window.dispatchEvent(new Event('resize'));
            });
                },
                methods: {
                    addRow: function() {
                        if(this.nbre_vitrines > this.images.length)
                            this.images.push([
                                {filled: '0'},
                                {filled: '0'}
                            ]);
                    },
                    removeRow: function(key) {
                        if (this.images.length > 1)
                            this.images.pop(key);
                    },
                    addFile: function(img) {
                        img.filled = 1;
                    },
                    removeFile: function(img){
                        img.filled = 0;
                    },
                    checkAllFiles: function(e){
                        e.preventDefault();
                        var all_required_files_are_uploaded = true;
                        this.images.forEach(function(d, i){
                            d.forEach(function(d1, i1){
                                if(d1.filled == 0){
                                    all_required_files_are_uploaded = false;
                                }
                            })
                        });

                        if(all_required_files_are_uploaded)
                            document.getElementById('pharmacieModel').submit();
                        return false;
                    }
                },
            });
        });
    </script>

@endsection