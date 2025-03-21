<?php

$prefix = env('PREFIX_URL', 'sipta');

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'AdminLTE 3',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '',
    'logo_img' => 'vendor/adminlte/dist/img/polban.png',
    'logo_img_class' => 'brand-image float-none',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => false,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/polban2.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 746,
            'height' => 1042,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'bg-gradient-dark',
    'classes_auth_header' => '',
    'classes_auth_body' => 'bg-gradient-dark',
    'classes_auth_footer' => 'text-center',
    'classes_auth_icon' => 'fa-fw text-light',
    'classes_auth_btn' => 'btn-flat btn-light',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'sm',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => env('PREFIX_URL', 'sipta') . '/',
    'logout_url' => env('PREFIX_URL', 'sipta') . '/logout',
    'login_url' => env('PREFIX_URL', 'sipta') . '/login',
    'register_url' => env('PREFIX_URL', 'sipta') . '/register',
    'password_reset_url' => env('PREFIX_URL', 'sipta') . '/password/reset',
    'password_email_url' => env('PREFIX_URL', 'sipta') . '/password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type' => 'navbar-search',
            'text' => 'search',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text' => 'blog',
            'url' => $prefix . '/admin/blog',
            'can' => 'manage-blog',
        ],
        ['header' => 'Layanan Tugas Akhir'],
        [
            'text' => 'Pengajuan dan Alokasi Pembimbing',
            // 'url' => 'admin/pages',
            'icon' => 'fas fa-fw fa-file',
            'submenu' => [
                [
                    'text' => 'Pengajuan Pembimbing',
                    'url' => 'admin/pages',
                    // 'icon' => 'far fa-fw fa-file',
                ],
                [
                    'text' => 'Alokasi Pembimbing',
                    'url' => 'admin/pages',
                    // 'icon' => 'far fa-fw fa-file',
                ],
            ]
            // 'label' => 4,
            // 'label_color' => 'success',
        ],
        [
            'text' => 'Kelola Pengajuan Berkas',
            // 'url' => 'admin/pages',
            'icon' => 'fas fa-fw fa-file',
            'submenu' => [
                [
                    'text' => 'Pengajuan Berkas Seminar 3',
                    'url' => 'kelola-pengajuan-berkas/seminar-3',
                    // 'icon' => 'far fa-fw fa-file',
                ],
                [
                    'text' => 'Pengajuan Berkas Sidang Akhir',
                    'url' => 'kelola-pengajuan-berkas/sidang-akhir',
                    // 'icon' => 'far fa-fw fa-file',
                ],
            ]
            // 'label' => 4,
            // 'label_color' => 'success',
        ],
        [
            'text' => 'Kelola Pengajuan Jadwal',
            // 'url' => 'admin/pages',
            'icon' => 'fas fa-fw fa-file',
            'submenu' => [
                [
                    'text' => 'Pengajuan Jadwal Seminar 3',
                    'url' => 'admin/pages',
                    // 'icon' => 'far fa-fw fa-file',
                ],
                [
                    'text' => 'Pengajuan Jadwal Sidang Akhir',
                    'url' => 'admin/pages',
                    // 'icon' => 'far fa-fw fa-file',
                ],
            ]
            // 'label' => 4,
            // 'label_color' => 'success',
        ],
        [
            'text' => 'Pengelolaan dan Penjadwalan Ruangan',
            'icon' => 'fas fa-home',
            'submenu' => [
                [
                    'text' => 'Daftar Kesediaan dan Pengajuan',
                    'url' => $prefix . '/#',
                    'submenu' => [
                        [
                            'text' => 'Daftar Kesediaan Membimbing',
                            'url' => $prefix . '/PengajuanAlokasiPembimbing/daftar-kesediaan-membimbing/',
                        ],
                        [
                            'text' => 'Daftar Pengajuan Dosen Pembimbing',
                            'url' => $prefix . '/PengajuanAlokasiPembimbing/DaftarPengajuanDosbing/',
                        ],
                    ],
                ],
                [
                    'text' => 'Formulir Pengajuan dan Kesediaan',
                    'url' => $prefix . '/#',
                    'submenu' => [
                        [
                            'text' => 'Formulir Pengajuan Pembimbing',
                            'url' => $prefix . '/PengajuanAlokasiPembimbing/pengajuan-pembimbing/data-kelompok',
                        ],
                        [
                            'text' => 'Formulir Kesediaan Membimbing',
                            'url' => $prefix . '/PengajuanAlokasiPembimbing/kesediaan-membimbing/minat-bidang',
                        ],
                    ],
                ],
                [
                    'text' => 'Pengelolaan Periode',
                    'url' => $prefix . '/PengajuanAlokasiPembimbing/pengelolaan-periode/',
                ],
                [
                    'text' => 'Alokasi Dosen Pembimbing',
                    'url' => $prefix . '/PengajuanAlokasiPembimbing/alokasi-pembimbing',
                ],
            ]
        ],
        [
            'text' => 'Perencanaan dan Pelaksanaan Seminar dan Sidang',
            'icon' => 'fas fa-fw fa-file',
            'submenu' => [
                [
                    'text' => 'Pembatalan Jadwal Seminar 3',
                    'url' => $prefix . '/batal-jadwal-seminar',
                    'can' => 'dosen'
                ],
                [
                    'text' => 'Pembatalan Jadwal Sidang',
                    'url' => $prefix . '/batal-jadwal-sidang',
                    'can' => 'dosen'
                ],
                [
                    'text' => 'Persetujuan Pembatalan Seminar 3',
                    'url' => $prefix . '/persetujuan-pembatalan-jadwal-seminar',
                    'can' => 'koordinator_ta'
                ],
                [
                    'text' => 'Persetujuan Pembatalan Sidang',
                    'url' => $prefix . '/persetujuan-pembatalan-jadwal-sidang',
                    'can' => 'koordinator_ta'
                ],
                [
                    'text' => 'Pengajuan',
                    'url' => $prefix . '/pengajuan',
                    'can' => 'all_mahasiswa',
                    'icon' => 'fas fa-fw fa-file',
                ],
                [
                    'text' => 'Verifikasi Berkas Pengajuan Mahasiswa',
                    'url' => $prefix . '/verifikasi-berkas',
                    'can' => 'mahasiswa_ta',
                ],
                [
                    'text' => 'Penilaian',
                    'url' => $prefix . '/DosenTabelPenilaian',
                    'can' => 'dosen',
                ],
                [
                    'text' => 'Rekap Berita Acara Seminar 3',
                    'url' => $prefix . '/rekap-berita-acara-seminar-3',
                    'can' => 'koordinator_ta',
                ],
                [
                    'text' => 'Rekap Berita Acara Sidang TA',
                    'url' => $prefix . '/rekap-berita-acara-sidang-ta',
                    'can' => 'koordinator_ta',
                ],
                [
                    'text' => 'Berita Acara Seminar 3',
                    'url' => $prefix . '/berita-acara-pelaksanaan-seminar3',
                    'can' => 'all_mahasiswa',
                ],
                [
                    'text' => 'Berita Acara Sidang TA',
                    'url' => $prefix . '/berita-acara-pelaksanaan-sidang-ta',
                    'can' => 'all_mahasiswa',
                ]
            ]
        ],
        [
            'text' => 'Kelola Penilaian Tugas Akhir',
            'icon' => 'fas fa-fw fa-file',
            'submenu' => [
                [
                    'text' => 'Kelola Penilaian Tugas Akhir',
                    'submenu' => [
                        [
                            'text' => 'Monitoring Mahasiswa',
                            'url' => $prefix . '/KelolaPenilaianTA/monitoring-mahasiswa',
                        ],
                        [
                            'text' => 'Monitoring Feedback',
                            'url' => $prefix . '/KelolaPenilaianTA/monitoring-feedback',
                        ],
                        [
                            'text' => 'Monitoring Rubrik',
                            'url' => $prefix . '/KelolaPenilaianTA/monitoring-rubrik',
                        ],
                    ],
                ],
                [
                    'text' => 'Formulir Penilaian',
                    'url' => $prefix . '/KelolaPenilaianTA/fomulir-penilaian',
                ],
                [
                    'text' => 'Pengelolaan Nilai',
                    'url' => $prefix . '/KelolaPenilaianTA/pengelolaan-nilai',
                ],
                [
                    'text' => 'Rekapitulasi Nilai',
                    'url' => $prefix . '/KelolaPenilaianTA/rekapitulasi-nilai',
                ],
            ]
        ],
        [
            'text' => 'Koordinator Kelola Pengajuan Jadwal',
            // 'url' => 'admin/pages',
            'icon' => 'fas fa-fw fa-file',
            'submenu' => [
                [
                    'text' => 'Pengajuan Jadwal Seminar 3',
                    'url' => '/koordinator-kelola-pengajuan-jadwal/seminar-3',
                    // 'icon' => 'far fa-fw fa-file',
                ],
                [
                    'text' => 'Pengajuan Jadwal Sidang Akhir',
                    'url' => '/koordinator-kelola-pengajuan-jadwal/sidang-akhir',
                    // 'icon' => 'far fa-fw fa-file',
                ],
            ],
            'can' => 'koordinator_ta',
            // 'label' => 4,
            // 'label_color' => 'success',
        ],
        [
            'text' => 'Dosen Kelola Pengajuan Jadwal',
            // 'url' => 'admin/pages',
            'icon' => 'fas fa-fw fa-file',
            'submenu' => [
                [
                    'text' => 'Pengajuan Jadwal Seminar 3',
                    'url' => '/kelola-pengajuan-jadwal-pembimbing/seminar-3',
                    // 'icon' => 'far fa-fw fa-file',
                ],
                [
                    'text' => 'Pengajuan Jadwal Sidang Akhir',
                    'url' => '/kelola-pengajuan-jadwal-pembimbing/sidang-akhir',
                    // 'icon' => 'far fa-fw fa-file',
                ],
            ],
            'can' => 'akses-dosen-kelola-pengajuan-jadwal',
            // 'label' => 4,
            // 'label_color' => 'success',
        ],
        [
            'text' => 'Timeline',
            'url' => $prefix . '/timeline',
            'icon' => 'fas fa-fw fa-calendar',
            // 'label' => 4,
            // 'label_color' => 'success',
        ],
        [
            'text' => 'Repository Tugas Akhir',
            'url' => $prefix . '/repository',
            'icon' => 'fas fa-book',
            // 'label' => 4,
            // 'label_color' => 'success',
        ],
        [
            'text' => 'Artefak',
            'url' => $prefix . '/#',
            'icon' => 'fas fa-fw fa-folder',
            'submenu' => [
                [
                    'text' => 'Kelola Dokumen dan FTA',
                    'url' => $prefix . '/artefak',
                ],
            ]
        ],
        [
            'text' => 'Cek Plagiarisme',
            'url' => $prefix . '/cek-plagiarisme',
            'icon' => 'fas fa-fw fa-file',
        ],
        [
            'text' => 'Penentuan Ambang Batas Plagiarisme',
            'url' => $prefix . '/penentuan-ambang-batas',
            'icon' => 'fas fa-scroll',
        ],
        ['header' => 'Pengaturan Pengguna'],
        [
            'text' => 'User Management',
            'icon' => 'fas fa-user',
            'submenu' => [
           
                [
                    'text' => 'Program Studi',
                    'url'  => $prefix . '/program-studi',
                    'icon' => 'fas fa-school',
                    'can' => 'admin',
                ],
                [
                    'text' => 'Kelola KBK',
                    'url'  => $prefix . '/kelola-kbk',
                    'icon' => 'fas fa-sitemap',
                    'can' => 'admin',
                ],
                [
                    'text' => 'Pengajuan Pisah KoTA',
                    'url' => $prefix . '/pengajuan-pisah-kota',
                    'icon' => 'fas fa-sign-out-alt',
                    'can' => 'koordinator_ta',
                ],
                [
                    'text' => 'Rekrut Anggota KoTA',
                    'url' => $prefix . '/perekrutan-anggota-kota',
                    'icon' => 'fas fa-users',
                    'can' => 'mahasiswa_non_ta',
                ],
                [
                    'text' => 'Detail KoTA',
                    'url'  => $prefix . '/kota-saya',
                    'icon' => 'fas fa-info',
                    'can' => 'mahasiswa_kota',
                ],
                [
                    'text' => 'Manajemen KoTA',
                    'url' => $prefix . '/management-kota',
                    'icon' => 'fas fa-users',
                    'can' => 'koordinator_ta',
                ],
                [
                    'text' => 'Manajemen Akun Dosen',
                    'url' => $prefix . '/manajemen-akun-dosen',
                    'icon' => 'fas fa-user-tie',
                    'can' => 'admin'
                ],
                [
                    'text' => 'Manajemen Akun Mahasiswa',
                    'url' => $prefix . '/manajemen-akun-mahasiswa',
                    'icon' => 'fas fa-user-graduate',
                    'can' => 'admin'
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
