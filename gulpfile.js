const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');
// require('./elixir-extensions');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {
    /**
     * Copy needed files from /node directories
     * to /public directory.
     */
    mix
    .copy(
        'node_modules/font-awesome/fonts',
        'public/build/fonts'
    )
    .copy(
        'resources/metro/assets/global/plugins/simple-line-icons/fonts/',
        'public/build/css/fonts'
    )
    .copy(
        'node_modules/bootstrap-sass/assets/fonts/bootstrap',
        'public/build/fonts'
    )
    .copy(
        'resources/metro/assets/layouts/layout/img/sidebar_*',
        'public/img'
    )
    .copy(
        'resources/metro/assets/global/plugins/datatables/images/*.png',
        'public/build/plugins/datatables/images'
    )
    .copy(
        'resources/metro/assets/global/plugins/jstree/dist/themes/default/32px.png',
        'public/build/css')
    .copy(
        'resources/metro/assets/global/plugins/cubeportfolio/img/*.gif',
        'public/build/img')
    .copy(
        'resources/metro/assets/global/plugins/cubeportfolio/img/*.png',
        'public/build/img')
    /**
     * Process frontend SCSS stylesheets
     */
    .sass([
        'frontend/app.scss',
        'plugin/sweetalert/sweetalert.scss'
    ], 'resources/assets/css/frontend/app.css')

    .sass([
        'plugin/sweetalert/sweetalert.scss'
    ], 'resources/assets/css/frontend/sweetalert.css')
    /**
     * Combine pre-processed frontend CSS files
     */
    .styles([
        '../../metro/assets/global/plugins/font-awesome/css/font-awesome.min.css',
        '../../metro/assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        '../../metro/assets/global/plugins/bootstrap/css/bootstrap.min.css',
        '../../metro/assets/global/plugins/uniform/css/uniform.default.css',
        '../../metro/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        '../../metro/assets/global/css/components-md.min.css',
        '../../metro/assets/global/css/plugins-md.min.css',
        '../../metro/assets/layouts/layout/css/layout.min.css',
        '../../metro/assets/layouts/layout/css/themes/darkblue.min.css',
        '../../metro/assets/layouts/layout/css/custom.min.css',
        '../../metro/assets/global/plugins/cubeportfolio/css/cubeportfolio.min.css',
        'resources/assets/css/frontend/sweetalert.css',
        '../../metro/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
        '../../metro/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
        '../../metro/assets/global/plugins/jstree/dist/themes/default/style.min.css',
        '../../metro/assets/global/plugins/select2/css/select2.min.css',
        '../../metro/assets/global/plugins/select2/css/select2-bootstrap.min.css',

    ], 'public/css/frontend.css')

    /**
     * Pack it up
     * Saves to a dist folder in resources, it is then combined and placed in public
     */
    .webpack('frontend/app.js', './resources/assets/js/dist/frontend.js')

    /**
     * Combine frontend scripts
     */
    .scripts([
        'dist/frontend.js',
        'plugin/sweetalert/sweetalert.min.js',
        'plugins.js',

        '../../metro/assets/global/plugins/jquery.min.js',
        '../../metro/assets/global/plugins/js.cookie.min.js',
        '../../metro/assets/global/plugins/bootstrap/js/bootstrap.min.js',
        '../../metro/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
        '../../metro/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        '../../metro/assets/global/plugins/jquery.blockui.min.js',
        '../../metro/assets/global/plugins/uniform/jquery.uniform.min.js',
        '../../metro/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        '../../metro/assets/global/scripts/app.min.js',
        '../../metro/assets/layouts/layout/scripts/layout.min.js',
        '../../metro/assets/layouts/layout/scripts/demo.min.js',
        '../../metro/assets/layouts/global/scripts/quick-sidebar.min.js',

        '../../metro/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
        '../../metro/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
        
        '../../metro/assets/global/plugins/datatables/datatables.min.js',
        '../../metro/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js',
        '../../metro/assets/global/plugins/cubeportfolio/js/jquery.cubeportfolio.min.js',
        '../../metro/assets/global/plugins/select2/js/select2.full.min.js',
        
        'directive.js',
        'frontend/portfolio.pack.min.js'
    ], 'public/js/frontend.js')
    .copy(
        'resources/assets/js/frontend/calendar.js',
        'public/js'
    )
    /**
     * Process backend SCSS stylesheets
     */
    .sass([
        'backend/app.scss',
        'plugin/toastr/toastr.scss',
        'plugin/sweetalert/sweetalert.scss'
    ], 'resources/assets/css/backend/app.css')

    /**
     * Combine pre-processed backend CSS files
     */
    .styles([
        'backend/app.css'
    ], 'public/css/backend.css')

    /**
     * Pack it up
     * Saves to a dist folder in resources, it is then combined and placed in public
     */
    .webpack('backend/app.js', './resources/assets/js/dist/backend.js')

    /**
     * Combine backend scripts
     */
    .scripts([
        'dist/backend.js',
        'plugin/sweetalert/sweetalert.min.js',
        'plugin/toastr/toastr.min.js',
        'plugins.js',
        'backend/custom.js'
    ], 'public/js/backend.js')

    /**
     * Apply version control
     */
    .version([
        "public/css/frontend.css",
        "public/js/frontend.js",
        "public/css/backend.css",
        "public/js/backend.js",
    ]);

    /**
     * Run tests
     */
    // .phpUnit();
});