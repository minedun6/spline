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
               {{ Form::open(['route' => ['frontend.pharmacies.update', $pharmacie->id], 'id' => 'pharmacieModel','class' => 'form-horizontal', 'method'=>'put','files' => true]) }}
                    <div class="form-body" v-cloak>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Nom</label>
                            <div class="col-md-9">
                                <input type="text" name="nom" required="true" value="{{$pharmacie->nom}}" class="form-control" placeholder="" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Secteur</label>
                            <div class="col-md-9">
                                <select class="form-control" name="secteur" data-live-search="true">
                                    <option @if($pharmacie->secteur == "TUNIS 1-CENTRE VILLE") selected @endif> TUNIS 1-CENTRE VILLE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 1-LA FAYETTE") selected @endif> TUNIS 1-LA FAYETTE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 1-BEB KHADRA") selected @endif> TUNIS 1-BEB KHADRA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 1-BEB BNET") selected @endif> TUNIS 1-BEB BNET</option>
                                    <option @if($pharmacie->secteur == "TUNIS 1-MONPLAISIR") selected @endif> TUNIS 1-MONPLAISIR</option>
                                    <option @if($pharmacie->secteur == "TUNIS 1-OMRANE") selected @endif> TUNIS 1-OMRANE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 1-BEB SAADOUN") selected @endif> TUNIS 1-BEB SAADOUN</option>
                                    <option @if($pharmacie->secteur == "TUNIS 1-AUTRE") selected @endif> TUNIS 1-AUTRE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 2-MARSA") selected @endif> TUNIS 2-MARSA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 2-CARTHAGE") selected @endif> TUNIS 2-CARTHAGE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 2-LAC") selected @endif> TUNIS 2-LAC</option>
                                    <option @if($pharmacie->secteur == "TUNIS 2-GAMMARTH") selected @endif> TUNIS 2-GAMMARTH</option>
                                    <option @if($pharmacie->secteur == "TUNIS 2-AIN ZAGHOUAN") selected @endif> TUNIS 2-AIN ZAGHOUAN</option>
                                    <option @if($pharmacie->secteur == "TUNIS 2-KRAM") selected @endif> TUNIS 2-KRAM</option>
                                    <option @if($pharmacie->secteur == "TUNIS 2-LA GOULETTE") selected @endif> TUNIS 2-LA GOULETTE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 2-AUTRE") selected @endif> TUNIS 2-AUTRE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 3-ARIANA") selected @endif> TUNIS 3-ARIANA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 3-SOUKRA") selected @endif> TUNIS 3-SOUKRA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 3-BORJ LOUZIR") selected @endif> TUNIS 3-BORJ LOUZIR</option>
                                    <option @if($pharmacie->secteur == "TUNIS 3-RAOUED") selected @endif> TUNIS 3-RAOUED</option>
                                    <option @if($pharmacie->secteur == "TUNIS 3-GHAZELA") selected @endif> TUNIS 3-GHAZELA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 3-DIAR SOUKRA") selected @endif> TUNIS 3-DIAR SOUKRA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 3-AUTRE") selected @endif> TUNIS 3-AUTRE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 4-MANAR") selected @endif> TUNIS 4-MANAR</option>
                                    <option @if($pharmacie->secteur == "TUNIS 4-MENZAH 1_4 ") selected @endif> TUNIS 4-MENZAH 1_4 </option>
                                    <option @if($pharmacie->secteur == "TUNIS 4-MENZAH 5-9") selected @endif> TUNIS 4-MENZAH 5-9</option>
                                    <option @if($pharmacie->secteur == "TUNIS 4-ENNASR") selected @endif> TUNIS 4-ENNASR</option>
                                    <option @if($pharmacie->secteur == "TUNIS 4-CITE KHADHRA") selected @endif> TUNIS 4-CITE KHADHRA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 4-MUTUELLE VILLE") selected @endif> TUNIS 4-MUTUELLE VILLE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 4-CITE MAHRAJEN") selected @endif> TUNIS 4-CITE MAHRAJEN</option>
                                    <option @if($pharmacie->secteur == "TUNIS 4-IBN KHALDOUN") selected @endif> TUNIS 4-IBN KHALDOUN</option>
                                    <option @if($pharmacie->secteur == "TUNIS 4-OMRANE SUP") selected @endif> TUNIS 4-OMRANE SUP</option>
                                    <option @if($pharmacie->secteur == "TUNIS 4-JARDINS D'EL MENZAH") selected @endif> TUNIS 4-JARDINS D'EL MENZAH</
                                    <option @if($pharmacie->secteur == "TUNIS 4-AUTRE") selected @endif> TUNIS 4-AUTRE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-BEN AROUS") selected @endif> TUNIS 5-BEN AROUS</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-MEGRINE") selected @endif> TUNIS 5-MEGRINE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-MOUROUJ") selected @endif> TUNIS 5-MOUROUJ</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-RADES") selected @endif> TUNIS 5-RADES</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-EZZAHRA") selected @endif> TUNIS 5-EZZAHRA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-BORJ CEDRIA") selected @endif> TUNIS 5-BORJ CEDRIA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-HAMMAM CHOT") selected @endif> TUNIS 5-HAMMAM CHOT</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-MORNEG") selected @endif> TUNIS 5-MORNEG</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-FOUCHENA") selected @endif> TUNIS 5-FOUCHENA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-MHAMDIA") selected @endif> TUNIS 5-MHAMDIA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 5-AUTRE") selected @endif> TUNIS 5-AUTRE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-BARDO") selected @endif> TUNIS 6-BARDO</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-ETADHAMEN") selected @endif> TUNIS 6-ETADHAMEN</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-MNIHLA") selected @endif> TUNIS 6-MNIHLA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-CITE ZOUHOUR") selected @endif> TUNIS 6-CITE ZOUHOUR</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-ZAHROUNI") selected @endif> TUNIS 6-ZAHROUNI</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-OMRANE SUP") selected @endif> TUNIS 6-OMRANE SUP</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-SIDI HASSINE") selected @endif> TUNIS 6-SIDI HASSINE</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-DOUAR HICHER") selected @endif> TUNIS 6-DOUAR HICHER</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-MANOUBA") selected @endif> TUNIS 6-MANOUBA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-JDAIDA") selected @endif> TUNIS 6-JDAIDA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-TEBOURBA") selected @endif> TUNIS 6-TEBOURBA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-DENDEN") selected @endif> TUNIS 6-DENDEN</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-AGBA") selected @endif> TUNIS 6-AGBA</option>
                                    <option @if($pharmacie->secteur == "TUNIS 6-AUTRE") selected @endif> TUNIS 6-AUTRE</option>
                                    <option @if($pharmacie->secteur == "BIZERTE-BIZERTE") selected @endif> BIZERTE-BIZERTE</option>
                                    <option @if($pharmacie->secteur == "BIZERTE-M.BOURGUIBA") selected @endif> BIZERTE-M.BOURGUIBA</option>
                                    <option @if($pharmacie->secteur == "BIZERTE-MATEUR") selected @endif> BIZERTE-MATEUR</option>
                                    <option @if($pharmacie->secteur == "BIZERTE-RAS JBEL") selected @endif> BIZERTE-RAS JBEL</option>
                                    <option @if($pharmacie->secteur == "BIZERTE-AUTRE") selected @endif> BIZERTE-AUTRE</option>
                                    <option @if($pharmacie->secteur == "CAP BON-NABEUL") selected @endif> CAP BON-NABEUL</option>
                                    <option @if($pharmacie->secteur == "CAP BON-DAR CHAABANE") selected @endif> CAP BON-DAR CHAABANE</option>
                                    <option @if($pharmacie->secteur == "CAP BON-MREZGA") selected @endif> CAP BON-MREZGA</option>
                                    <option @if($pharmacie->secteur == "CAP BON-BENI KHIAR") selected @endif> CAP BON-BENI KHIAR</option>
                                    <option @if($pharmacie->secteur == "CAP BON-HAMMAMET") selected @endif> CAP BON-HAMMAMET</option>
                                    <option @if($pharmacie->secteur == "CAP BON-BOUFICHA") selected @endif> CAP BON-BOUFICHA</option>
                                    <option @if($pharmacie->secteur == "CAP BON-M.BOUZELFA") selected @endif> CAP BON-M.BOUZELFA</option>
                                    <option @if($pharmacie->secteur == "CAP BON-HAOUARIA") selected @endif> CAP BON-HAOUARIA</option>
                                    <option @if($pharmacie->secteur == "CAP BON-KELIBIA") selected @endif> CAP BON-KELIBIA</option>
                                    <option @if($pharmacie->secteur == "CAP BON-M.TEMIM") selected @endif> CAP BON-M.TEMIM</option>
                                    <option @if($pharmacie->secteur == "CAP BON-ENCHAA") selected @endif> CAP BON-ENCHAA</option>
                                    <option @if($pharmacie->secteur == "CAP BON-KORBA") selected @endif> CAP BON-KORBA</option>
                                    <option @if($pharmacie->secteur == "CAP BON-MAAMOURA") selected @endif> CAP BON-MAAMOURA</option>
                                    <option @if($pharmacie->secteur == "CAP BON-MIDA") selected @endif> CAP BON-MIDA</option>
                                    <option @if($pharmacie->secteur == "CAP BON-AUTRE") selected @endif> CAP BON-AUTRE</option>
                                    <option @if($pharmacie->secteur == "NORD OUEST-BEJA") selected @endif> NORD OUEST-BEJA</option>
                                    <option @if($pharmacie->secteur == "NORD OUEST-JENDOUBA") selected @endif> NORD OUEST-JENDOUBA</option>
                                    <option @if($pharmacie->secteur == "NORD OUEST-KEF") selected @endif> NORD OUEST-KEF</option>
                                    <option @if($pharmacie->secteur == "NORD OUEST-BOUSSELEM") selected @endif> NORD OUEST-BOUSSELEM</option>
                                    <option @if($pharmacie->secteur == "NORD OUEST-MJEZ EL BEB") selected @endif> NORD OUEST-MJEZ EL BEB</option>
                                    <option @if($pharmacie->secteur == "NORD OUEST-SELIANA") selected @endif> NORD OUEST-SELIANA</option>
                                    <option @if($pharmacie->secteur == "NORD OUEST-AUTRE") selected @endif> NORD OUEST-AUTRE</option>
                                    <option @if($pharmacie->secteur == "SAHEL-MOKNINE") selected @endif> SAHEL-MOKNINE</option>
                                    <option @if($pharmacie->secteur == "SAHEL-JAMMEL") selected @endif> SAHEL-JAMMEL</option>
                                    <option @if($pharmacie->secteur == "SAHEL-BENNENE") selected @endif> SAHEL-BENNENE</option>
                                    <option @if($pharmacie->secteur == "SAHEL-ZAOUIET SOUSSE") selected @endif> SAHEL-ZAOUIET SOUSSE</option>
                                    <option @if($pharmacie->secteur == "SAHEL-SOUSSE") selected @endif> SAHEL-SOUSSE</option>
                                    <option @if($pharmacie->secteur == "SAHEL-MONASTIR") selected @endif> SAHEL-MONASTIR</option>
                                    <option @if($pharmacie->secteur == "SAHEL-MAHDIA") selected @endif> SAHEL-MAHDIA</option>
                                    <option @if($pharmacie->secteur == "SAHEL-KSAR HELAL") selected @endif> SAHEL-KSAR HELAL</option>
                                    <option @if($pharmacie->secteur == "SAHEL-KALAA KBIRA") selected @endif> SAHEL-KALAA KBIRA</option>
                                    <option @if($pharmacie->secteur == "SAHEL-KALAA SGHIRA") selected @endif> SAHEL-KALAA SGHIRA</option>
                                    <option @if($pharmacie->secteur == "SAHEL-SAYEDA") selected @endif> SAHEL-SAYEDA</option>
                                    <option @if($pharmacie->secteur == "SAHEL-EL JEM") selected @endif> SAHEL-EL JEM</option>
                                    <option @if($pharmacie->secteur == "SAHEL-SIDI EL HENI") selected @endif> SAHEL-SIDI EL HENI</option>
                                    <option @if($pharmacie->secteur == "SAHEL-AUTRE") selected @endif> SAHEL-AUTRE</option>
                                    <option @if($pharmacie->secteur == "CENTRE-KAIROUAN") selected @endif> CENTRE-KAIROUAN</option>
                                    <option @if($pharmacie->secteur == "CENTRE-KASSERINE") selected @endif> CENTRE-KASSERINE</option>
                                    <option @if($pharmacie->secteur == "CENTRE-SBITLA") selected @endif> CENTRE-SBITLA</option>
                                    <option @if($pharmacie->secteur == "CENTRE-GAFSA") selected @endif> CENTRE-GAFSA</option>
                                    <option @if($pharmacie->secteur == "CENTRE-EL KTAR") selected @endif> CENTRE-EL KTAR</option>
                                    <option @if($pharmacie->secteur == "CENTRE-SIDI BOUZID") selected @endif> CENTRE-SIDI BOUZID</option>
                                    <option @if($pharmacie->secteur == "CENTRE-AUTRE") selected @endif> CENTRE-AUTRE</option>
                                    <option @if($pharmacie->secteur == "SUD-GABES") selected @endif> SUD-GABES</option>
                                    <option @if($pharmacie->secteur == "SUD-HAMMA GABES") selected @endif> SUD-HAMMA GABES</option>
                                    <option @if($pharmacie->secteur == "SUD-MEDNINE") selected @endif> SUD-MEDNINE</option>
                                    <option @if($pharmacie->secteur == "SUD-DJERBA MIDOUN") selected @endif> SUD-DJERBA MIDOUN</option>
                                    <option @if($pharmacie->secteur == "SUD-DJERBA H.SOUK") selected @endif> SUD-DJERBA H.SOUK</option>
                                    <option @if($pharmacie->secteur == "SUD-DJERBA AJIM") selected @endif> SUD-DJERBA AJIM</option>
                                    <option @if($pharmacie->secteur == "SUD-ZARZIS") selected @endif> SUD-ZARZIS</option>
                                    <option @if($pharmacie->secteur == "SUD-TATAOUINE") selected @endif> SUD-TATAOUINE</option>
                                    <option @if($pharmacie->secteur == "SUD-NAFTA") selected @endif> SUD-NAFTA</option>
                                    <option @if($pharmacie->secteur == "SUD-SFAX") selected @endif> SUD-SFAX</option>
                                    <option @if($pharmacie->secteur == "SUD-JBENIANA") selected @endif> SUD-JBENIANA</option>
                                    <option @if($pharmacie->secteur == "SUD-M.HBIB") selected @endif> SUD-M.HBIB</option>
                                    <option @if($pharmacie->secteur == "SUD-GHANOUCH") selected @endif> SUD-GHANOUCH</option>
                                    <option @if($pharmacie->secteur == "SUD-BEN GARDEN") selected @endif> SUD-BEN GARDEN</option>
                                    <option @if($pharmacie->secteur == "SUD-BIR LAHMER") selected @endif> SUD-BIR LAHMER</option>
                                    <option @if($pharmacie->secteur == "SUD-MARETH") selected @endif> SUD-MARETH</option>
                                    <option @if($pharmacie->secteur == "SUD-SFAX") selected @endif> SUD-SFAX</option>
                                    <option @if($pharmacie->secteur == "SUD-AUTRE") selected @endif> SUD-AUTRE</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Adresse</label>
                            <div class="col-md-9">
                                <input type="text" name="adresse" value="{{$pharmacie->adresse}}" required="true" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Téléphone</label>
                            <div class="col-md-9">
                                <input type="text" name="telephone" value="{{$pharmacie->telephone}}" pattern="[0-9]{8}" required="true" class="form-control" placeholder="">
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
                                <div class="form-inline" v-for='vit, index in nbre_vitrines'>
                                    <div class="form-group">
                                        <input type="number" min="0" :value="vitrines_mesures[index].width" required="true" :name="'mesures['+(index+1)+'][width]'" class="form-control input-xsmall mesures"> largeur x <input type="number" min="0" :value="vitrines_mesures[index].height" required="true" :name="'mesures['+(index+1)+'][height]'" class="form-control input-xsmall mesures"> hauteur
                                        
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Photos 
                                <a class="btn-lg btn-circle btn-icon-only blue" @click="addRow()">
                                    <i class="icon-plus"></i>
                                </a>
                            </label>
                            <div class="col-md-9">
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th> Images avec mesures </th>
                                            <th> Images sans mesures </th>
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
                                <textarea class="form-control" name="note" rows="3">{{$pharmacie->note}}</textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Latitude</label>
                            <div class="col-md-9">
                                <div class="portlet light portlet-fit bordered">
                                    <div id="map" class="gmaps" style="position: relative; overflow: hidden;"></div>
                                </div>
                                <input type="hidden" required="true" value="{{$pharmacie->long}}" name="long" id="longitude" class="form-control"  placeholder="">
                                <input type="hidden" required="true" value="{{$pharmacie->lat}}" name="lat" id="latitude" class="form-control" placeholder="">
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
    <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Veuillez choisir l'emplacement de la pharmacie</h4>
                </div>
                <div class="modal-body"> 
                    <div id="map" style="height: 500px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Fermer</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
            $('.modal').on('shown.bs.modal', function (e) {
                window.dispatchEvent(new Event('resize'));
            });

            var pharmacieModel = new Vue({
                el: '#pharmacieModel',
                data: {
                    images_length: 1,
                    nbre_vitrines: @if($pharmacie->nbre_vitrines != 0) {{$pharmacie->nbre_vitrines}} @else 1 @endif,
                    images: [
                        [
                            {filled: '0'},
                            {filled: '0'}
                        ]
                    ],
                    vitrines_mesures: {!!$vitrine_mesures!!}
                },
                mounted: function () {
                    var map = new GMaps({
                      div: '#map',
                      lat: @if($pharmacie->lat)  {{$pharmacie->lat}} @else 36.9189700 @endif,
                      lng: @if($pharmacie->long) {{$pharmacie->long}} @else 36.9189700 @endif,
                      zoom: 8,
                      width: 550,
                      height: 400
                    });
                    map.addMarker({
                      lat: @if($pharmacie->lat)  {{$pharmacie->lat}} @else 36.9189700 @endif,
                      lng: @if($pharmacie->long) {{$pharmacie->long}} @else 36.9189700 @endif,
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
                },
                watch: {
                    nbre_vitrines: function (val, oldVal){
                        if(this.vitrines_mesures.length < val){
                            this.vitrines_mesures.push({"width":0, "height": 0});
                        }else{
                            this.vitrines_mesures.pop();
                        }
                    }
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