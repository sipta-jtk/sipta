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
            'text' => '',
            'icon' => 'fas fa-bell',
            'topnav_right' => true,
            'id' => 'notificationBell',
            'classes' => 'nav-link',
        ],
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
        ['header' => 'Layanan Tugas Akhir', 'can' => 'user'],
        [
            'text' => 'Pengajuan dan Alokasi Pembimbing',
            'icon' => 'fas fa-home',
            'submenu' => [
                [
                    'text' => 'Daftar Kesediaan dan Pengajuan',
                    'url' => $prefix . '/#',
                    'icon' => 'fas fa-user-tag',
                    'submenu' => [
                        [
                            'text' => 'Daftar Kesediaan Membimbing',
                            'url' => $prefix . '/PengajuanAlokasiPembimbing/daftar-kesediaan-membimbing/',
                            'can' => 'koordinator_ta',
                            'icon' => 'fas fa-fw fa-table'
                        ],
                        [
                            'text' => 'Peminatan Menjadi Penguji',
                            'icon' => 'fas fa-fw fa-window-restore',
                            'url' => $prefix . '/PengajuanAlokasiPembimbing/daftar-pengajuan-dosbing/',
                            'can' => 'dosen',
                        ],
                    ],
                ],
                [
                    'text' => 'Formulir Pengajuan dan Kesediaan',
                    'url' => $prefix . '/#',
                    'icon' => 'fas fa-fw fa-file-alt',
                    'submenu' => [
                        [
                            'text' => 'Formulir Pengajuan Dosen Pembimbing',
                            'icon' => 'fas fa-fw fa-file-alt',
                            'url' => $prefix . '/PengajuanAlokasiPembimbing/pengajuan-pembimbing/data-kelompok',
                            'can' => 'mahasiswa_ta',
                        ],
                        [
                            'text' => 'Formulir Kesediaan Membimbing',
                            'url' => $prefix . '/PengajuanAlokasiPembimbing/kesediaan-membimbing/minat-bidang',
                            'can' => 'dosen',
                            'icon' => 'fas fa-fw fa-file-alt'
                        ],
                    ],
                ],
                [
                    'text' => 'Pengelolaan Periode',
                    'url' => $prefix . '/PengajuanAlokasiPembimbing/pengelolaan-periode/',
                    'can' => 'koordinator_ta',
                    'icon' => 'fas fa-fw fa-clock'
                ],
                [
                    'text' => 'Alokasi Dosen Pembimbing',
                    'url' => $prefix . '/PengajuanAlokasiPembimbing/alokasi-pembimbing',
                    'can' =>'akses-alokasi',
                     'icon' => 'fas fa-chalkboard-teacher'
                ],
                [
                    'text' => 'Alokasi Dosen Penguji',
                    'url' => $prefix . '/PengajuanAlokasiPembimbing/alokasi-penguji',
                    'can' =>'akses-alokasi',
                     'icon' => 'fas fa-chalkboard-teacher'
                ],
                [
                    'text' => 'Jadwal Bimbingan Dosen Pembimbing',
                    'url' => $prefix . '/PengajuanAlokasiPembimbing/jadwal-dosen-membimbing',
                    'can' => 'mahasiswa_ta',
                    'icon' => 'fas fa-fw fa-calendar'
                ],
            ]
        ],
        [
            'text' => 'Perencanaan dan Pelaksanaan Seminar dan Sidang',
            'icon' => 'fas fa-solid fa-clipboard',
            'submenu' => [
                [
                // Sidebar POV Mahasiswa
                'text' => 'Seminar 3',
                'icon' => 'fas fa-chalkboard-teacher',
                'submenu' => [
                    [
                        'text' => 'Verifikasi Berkas Pengajuan Mahasiswa Seminar 3',
                        'url' => $prefix . '/verifikasi-berkas-3',
                        'can' => 'mahasiswa_ta',
                        'icon' => 'fas fa-fw fa-file',
                    ],
                    [
                        'text' => 'Pengajuan',
                        'url' => $prefix . '/pengajuan',
                        'can' => 'all_mahasiswa',
                        'icon' => 'fas fa-fw fa-file',
                    ],
                    [
                        'text' => 'Berita Acara Seminar 3',
                        'url' => $prefix . '/berita-acara-pelaksanaan-seminar-3',
                        'icon' => 'fas fa-calendar-check', // Icon Kalender cek menggambarkan isi berita acara
                        'can' => 'all_mahasiswa',
                    ]
                ],],
                [
                    // Sidebar POV Mahasiswa
                    'text' => 'Sidang Akhir',
                    'icon' => 'fas fa-solid fa-gavel',
                    'submenu' => [
                        [
                            'text' => 'Verifikasi Berkas Pengajuan Mahasiswa Sidang Akhir',
                            'url' => $prefix . '/verifikasi-berkas-sidang',
                            'can' => 'mahasiswa_ta',
                            'icon' => 'fas fa-fw fa-file',
                        ],
                        [
                            'text' => 'Pengajuan',
                            'url' => $prefix . '/pengajuan',
                            'can' => 'all_mahasiswa',
                            'icon' => 'fas fa-fw fa-file',
                        ],
                        [
                            'text' => 'Berita Acara Sidang TA',
                            'url' => $prefix . '/berita-acara-pelaksanaan-sidang-ta',
                            'icon' => 'fas fa-calendar-check', // Icon Kalender cek menggambarkan isi berita acara
                            'can' => 'all_mahasiswa',
                        ]
                    ],],
                // Sidebar POV Dosen dan Koor
                [
                    'text' => 'Seminar 3',
                    'icon' => 'fas fa-chalkboard-teacher',
                    'submenu' => [
                        [
                            'text' => 'Verifikasi Berkas Seminar 3',
                            'url' => $prefix . '/kelola-pengajuan-berkas/seminar-3',
                            'icon' => 'fas fa-fw fa-file',
                            'can' => 'koordinator_ta',
                        ],
                        [
                            'text' => 'Dosen Kelola Pengajuan Jadwal',
                            'icon' => 'fas fa-fw fa-file',
                            'submenu' => [
                                [
                                    'text' => 'Pengajuan Jadwal Seminar 3',
                                    'url' => $prefix . '/kelola-pengajuan-jadwal-pembimbing/seminar-3',
                                    'can' => 'akses-dosen-kelola-pengajuan-jadwal',
                                    'icon' => 'far fa-fw fa-file',
                                ],
                                [
                                    'text' => 'Jadwal Seminar 3',
                                    'url' => $prefix . '/batal-jadwal-seminar',
                                    'can' => 'dosen',
                                    'icon' => 'fas fa-regular fa-calendar'
                                ]
                            ],
                        ],
                        [
                            'text' => 'Koordinator Kelola Pengajuan Jadwal',
                            'icon' => 'fas fa-fw fa-file',
                            'submenu' => [
                                [
                                    'text' => 'Pengajuan Jadwal Seminar 3',
                                    'url' => $prefix . '/koordinator-kelola-pengajuan-jadwal/seminar-3',
                                    'can' => 'koordinator_ta',
                                    'icon' => 'far fa-fw fa-file',
                                ],
                                [
                                    'text' => 'Persetujuan Pembatalan Seminar 3',
                                    'url' => $prefix . '/persetujuan-pembatalan-jadwal-seminar',
                                    'can' => 'koordinator_ta',
                                    'icon' => 'fas fa-regular fa-calendar'
                                ]
                            ],
                        ],
                        [
                            'text' => 'Rekapitulasi Berita Acara Seminar 3',
                            'url' => $prefix . '/rekapitulasi-berita-acara-seminar-3',
                            'icon' => 'fas fa-clipboard-list',
                            'can' => 'koordinator_ta',
                        ],
                        [
                            'text' => 'Penilaian',
                            'url' => $prefix . '/dosen-tabel-penilaian/seminar-iii',
                            'can' => 'dosen',
                            'icon' => 'fas fa-fw fa-award',
                        ],  
                    ],
                ],
                [
                    'text' => 'Sidang Akhir',
                    'icon' => 'fas fa-solid fa-gavel',
                    'submenu' => [
                        [
                            'text' => 'Verifikasi Berkas Seminar Sidang Akhir',
                            'url' => $prefix . '/kelola-pengajuan-berkas/sidang-akhir',
                            'icon' => 'fas fa-fw fa-file',
                            'can' => 'koordinator_ta',
                        ],
                        [
                            'text' => 'Dosen Kelola Pengajuan Jadwal',
                            'icon' => 'fas fa-fw fa-file',
                            'submenu' => [
                                [
                                    'text' => 'Pengajuan Jadwal Sidang Akhir',
                                    'url' => $prefix . '/kelola-pengajuan-jadwal-pembimbing/sidang-akhir',
                                    'can' => 'akses-dosen-kelola-pengajuan-jadwal',
                                    'icon' => 'far fa-fw fa-file',
                                ],
                                [
                                    'text' => 'Jadwal Sidang',
                                    'url' => $prefix . '/batal-jadwal-sidang',
                                    'can' => 'dosen',
                                    'icon' => 'fas fa-regular fa-calendar'
                                ],
                            ],
                        ],
                        [
                            'text' => 'Koordinator Kelola Pengajuan Jadwal',
                            'icon' => 'fas fa-fw fa-file',
                            'submenu' => [
                                [
                                    'text' => 'Pengajuan Jadwal Sidang Akhir',
                                    'url' => $prefix . '/koordinator-kelola-pengajuan-jadwal/sidang-akhir',
                                    'can' => 'koordinator_ta',
                                    'icon' => 'far fa-fw fa-file',
                                ],
                                [
                                    'text' => 'Persetujuan Pembatalan Sidang',
                                    'url' => $prefix . '/persetujuan-pembatalan-jadwal-sidang',
                                    'can' => 'koordinator_ta',
                                    'icon' => 'fas fa-regular fa-calendar'
                                ],
                            ],
                        ],
                        [
                            'text' => 'Rekapitulasi Berita Acara Sidang TA',
                            'url' => $prefix . '/rekapitulasi-berita-acara-sidang-ta',
                            'icon' => 'fas fa-clipboard-list',
                            'can' => 'koordinator_ta',
                        ],    
                        [
                            'text' => 'Penilaian',
                            'url' => $prefix . '/dosen-tabel-penilaian/sidang-akhir',
                            'can' => 'dosen',
                            'icon' => 'fas fa-fw fa-award',
                        ],  
                    ],
                ],                          
            ]
        ],
        [
            'text' => 'Kelola Penilaian Tugas Akhir',
            'icon' => 'fas fa-fw fa-file-alt',
            'submenu' => [
                [
                    'text' => 'Monitoring Mahasiswa',
                    'url' => $prefix . '/kelola-penilaian-ta/monitoring/mahasiswa',
                    'can' => 'akses-penilaian-mahasiswa',
                    'icon' => 'fas fa-fw fa-user-check',
                ],
                [
                    'text' => 'Rekapitulasi Nilai',
                    'icon' => 'fas fa-fw fa-table',
                    'submenu' => [
                        [
                            'text' => 'Rekapitulasi Nilai Seminar dan Sidang',
                            'url' => $prefix . '/kelola-penilaian-ta/rekapitulasi-nilai-sidang',
                            'can' => 'akses-penilaian-koordinator-ta',
                            'icon' => 'fas fa-fw fa-chalkboard-teacher',  
                        ],
                        [
                            'text' => 'Rekapitulasi Nilai Akhir',
                            'url' => $prefix . '/kelola-penilaian-ta/rekapitulasi-nilai-akhir',
                            'can' => 'akses-penilaian-koordinator-ta',
                            'icon' => 'fas fa-fw fa-clipboard-check',
                        ],
                        [
                            'text' => 'Pengaturan Nilai Akhir',
                            'url' => $prefix . '/kelola-penilaian-ta/pengaturan-nilai-akhir',
                            'can' => 'akses-penilaian-koordinator-ta',
                            'icon' => 'fas fa-fw fa-sliders-h',
                        ],
                    ],
                ],
                [
                    'text' => 'Formulir Penilaian',
                    'url' => $prefix . '/kelola-penilaian-ta/formulir-penilaian',
                    'can' => 'akses-penilaian-koordinator-ta',
                    'icon' => 'fas fa-fw fa-clipboard-list',
                    'submenu' => [
                        [
                            'text' => 'Pengelolaan Formulir',
                            'url' => $prefix . '/kelola-penilaian-ta/formulir-penilaian',
                            'can' => 'akses-penilaian-koordinator-ta',
                            'icon' => 'fas fa-fw fa-edit',
                        ],
                        [
                            'text' => 'Pengelolaan Rubrik',
                            'url' => $prefix . '/kelola-penilaian-ta/formulir-penilaian/kelola-rubrik',
                            'can' => 'akses-penilaian-koordinator-ta',
                            'icon' => 'fas fa-fw fa-th-list',
                        ],
                    ]
                ],
                [
                    'text' => 'Pengelolaan Nilai',
                    'url' => $prefix . '/kelola-penilaian-ta/pengelolaan-nilai',
                    'can' => 'akses-penilaian-koordinator-ta',
                    'icon' => 'fas fa-fw fa-calculator',
                ]
            ]
        ],
        [
            'text' => 'KoTA Bimbingan',
            'url' => $prefix . '/kelola-penilaian-ta/monitoring/dosen-pembimbing',
            'can' => 'akses-monitoring-dosen-pembimbing',
            'icon' => 'fas fa-fw fa-user-check',
        ],
        [
            'text' => 'Timeline',
            'url' => $prefix . '/timeline',
            'icon' => 'fas fa-fw fa-calendar',
            'can' => 'user'

            // 'label' => 4,
            // 'label_color' => 'success',
            // 'label' => 4,
            // 'label_color' => 'success',
        ],
        [
            'text' => 'Repository Tugas Akhir',
            'icon' => 'fas fa-book',
            'submenu' => [
                [
                    'text' => 'List mahasiswa TA',
                    'url'  => $prefix . '/repository/dosen/kelompok-ta',
                    'icon' => 'fas fa-user-friends',
                    'can' => 'akses-koordinator-admin'
                ],
                [
                    'text' => 'List mahasiswa bimbingan',
                    'url'  => $prefix . '/repository/dosen/kelompok-ta-bimbingan',
                    'icon' => 'fas fa-user-friends',
                    'can' => 'pembimbing'
                ],
                [
                    'text' => 'List mahasiswa uji',
                    'url'  => $prefix . '/repository/dosen/kelompok-ta-uji',
                    'icon' => 'fas fa-user-friends',
                    'can' => 'penguji'
                ],
                [
                    'text' => 'Repository Mahasiswa',
                    'url'  => $prefix . '/repository/mahasiswa',
                    'can' => 'akses-sidebar-repo-mahasiswa'
                ],
                [
                    'text' => 'Log Aktivitas',
                    'url'  => $prefix . '/repository/koor-ta/log-aktivitas',
                    'icon' => 'fas fa-clock',
                    'can' => 'akses-koordinator-admin'
                ],
                [
                    'text' => 'Monitoring Penyimpanan',
                    'url'  => $prefix . '/repository/koor-ta/monitoring-penyimpanan',
                    'icon' => 'fas fa-database',
                    'can' => 'akses-koordinator-admin'
                ],
            ]
        ],
        // [
        //     'text' => 'Artefak',
        //     'url' => $prefix . '/#',
        //     'icon' => 'fas fa-fw fa-folder',
        //     'submenu' => [
        //         [
        //             'text' => 'Kelola Dokumen dan FTA',
        //             'url' => $prefix . '/artefak',
        //         ],
        //     ]
        // ],
        [
            'text' => 'Kelola Ruangan',
            'url' => $prefix . '/ruangan-service/ruangan',
            'icon' => 'fas fa-fw fa-building',
            'can' => 'admin',
        ],
        [
            'text' => 'Kalender Penjadwalan Ruangan',
            'url' => $prefix . '/ruangan-service/kalender',
            'icon' => 'fas fa-fw fa-calendar',
            'can' => 'user',
        ],
        [
            'text' => 'Cek Plagiarisme',
            'icon' => 'fas fa-fw fa-file',
            'can' => 'user',
            'submenu' => [
                [
                    'text' => 'Cek Plagiarisme',
                    'url' => $prefix . '/cek-plagiarisme',
                    'icon' => 'fas fa-fw fa-file',
                ],
                [
                    'text' => 'Penentuan Ambang Batas Plagiarisme',
                    'url' => $prefix . '/penentuan-ambang-batas',
                    'icon' => 'fas fa-scroll',
                    'can' => 'akses-koordinator-admin'
                ],
            ]
        ],
        ['header' => 'Pengaturan Pengguna', 'can' => 'user'],
        [
            'text' => 'User Management',
            'icon' => 'fas fa-user',
            'submenu' => [

                [
                    'text' => 'Program Studi',
                    'url' => $prefix . '/program-studi',
                    'icon' => 'fas fa-school',
                    'can' => 'admin',
                ],
                [
                    'text' => 'Kelola KBK',
                    'url' => $prefix . '/kelola-kbk',
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
                    'url' => $prefix . '/kota-saya',
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
                [
                    'text' => 'Log Aktivitas',
                    'url' => $prefix . '/log-aktivitas',
                    'icon' => 'fas fa-user-graduate',
                    'can' => 'admin'
                ],
                // [
                //     'text' => 'level_one',
                //     'url' => '#',
                // ],
            ],
        ],
         [
            'text' => 'Pendataan Mahasiswa',
            'icon' => 'fas fa-user-graduate',
            'submenu' => [
                [
                            'text' => 'Pendataan Mahasiswa',
                            'url' => $prefix . '/pendataan-mahasiswa',
                            'icon' => 'fas fa-user-graduate',
                            'can' => 'pemimpin'
                        ],
                        [
                            'text' => 'Pendataan KoTA',
                            'url' => $prefix . '/pendataan-kelompok-ta',
                            'icon' => 'fas fa-users',
                            'can' => 'pemimpin'
                        ],
            ]
        ],
        [
            'text' => 'Notifikasi dan Reminder',
            'icon' => 'fas fa-bell',
            'submenu' => [
                [
                    'text' => 'Pengaturan Notifikasi',
                    'url' => $prefix . '/notification/admin/settingawal',
                    'icon' => 'fas fa-pen',
                    'can' => 'admin',
                ],
                [
                    'text' => 'Log Notifikasi Admin',
                    'url' => $prefix . '/api/logAdmin',
                    'icon' => 'fas fa-clipboard-list',
                    'can' => 'admin',
                ],
                                [
                    'text' => 'Pengaturan Placeholder',
                    'url' => $prefix . '/notification-placeholders',
                    'icon' => 'fas fa-pen',
                    'can' => 'admin',
                ],
                
            ]
            // 'label' => 4,
            // 'label_color' => 'success',
        ],
        // ['header' => 'labels'],
        // [
        //     'text' => 'important',
        //     'icon_color' => 'red',
        //     'url' => '#',
        // ],
        // [
        //     'text' => 'warning',
        //     'icon_color' => 'yellow',
        //     'url' => '#',
        // ],
        // [
        //     'text' => 'information',
        //     'icon_color' => 'cyan',
        //     'url' => '#',
        // ],
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
