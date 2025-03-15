<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Components\DaftarKesediaanMembimbing;

use Illuminate\View\Component;

class DaftarKesediaanMembimbing extends Component
{



    public function __construct()
    {

    }

    public function render()
    {
        return view('PengajuanAlokasiPembimbing.views.DaftarKesediaanMembimbing.Components.TableKesediaan');

    }
}