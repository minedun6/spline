<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <li class="nav-item start {{ Active::pattern('/') }}">
                <a href="/" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">Accueil</span>
                    @if(Active::pattern('/'))
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            {{-- @role('Collaborateur', 'Administrator') --}}
            <li class="nav-item start {{ Active::pattern('commandes*') }}">
                <a href="{{ route('frontend.commandes.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-files-o"></i>
                    <span class="title">Commandes</span>
                    @if(Active::pattern('commandes*'))
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            {{-- @endauth --}}
            @role('Collaborateur')
            <li class="nav-item start {{ Active::pattern('products') }}">
                <a href="{{ route('frontend.products.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-shopping-cart"></i>
                    <span class="title">Produits</span>
                    @if(Active::pattern('products'))
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            @endauth
            @role('Crea')
            <li class="nav-item start {{ Active::pattern('pharmacies*') }}">
                <a href="{{ route('frontend.pharmacies.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-tree"></i>
                    <span class="title">Pharmacies</span>
                    @if(Active::pattern('pharmacies*'))
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            @endauth
            @role('Administrator')
            <li class="nav-item start {{ Active::pattern('delegues*') }}">
                <a href="{{ route('frontend.delegues.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-group"></i>
                    <span class="title">Délégués</span>
                    @if(Active::pattern('delegues*'))
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            @endauth
            @if(Auth::user()->hasRole('Poseur'))
            <li class="nav-item start {{ Active::pattern('planifications*') }}">
                <a href="{{ route('frontend.commandes.planifications') }}" class="nav-link nav-toggle">
                    <i class="fa fa-calendar"></i>
                    <span class="title">Planifications</span>
                    @if(Active::pattern('planifications*'))
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            @endif
            @if(!is_admin() and Auth::user()->hasRole('Poseur'))
            <li class="nav-item start {{ Active::pattern('mes-poses*') }}">
                <a href="{{ route('frontend.commandes.poses') }}" class="nav-link nav-toggle">
                    <i class="fa fa-calendar"></i>
                    <span class="title">Mes poses</span>
                    @if(Active::pattern('mes-poses*'))
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            @endif
            @if(is_client() and Auth::user()->collaborateur->is_manager )
            <li class="nav-item start {{ Active::pattern('collaborateurs*') }}">
                <a href="{{ route('frontend.collaborateurs.index') }}" class="nav-link nav-toggle">
                    <i class="fa fa-group"></i>
                    <span class="title">Mes collaborateurs</span>
                    @if(Active::pattern('collaborateurs*'))
                    <span class="selected"></span>
                    @endif
                </a>
            </li>
            @endif
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>