<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\PeriodePengajuan;
use App\Modules\Controller;
use Illuminate\View\View;

class PengelolaanPeriodeController extends Controller
{
    public function view_PengelolaanPeriode(): View
    {
        $data = [
            'periodes' => PeriodePengajuan::all()
        ];
        return view('PengajuanAlokasiPembimbing.views.PengelolaanPeriode.PengelolaanPeriode', $data);
    }

    public function save_PengelolaanPeriode($mode = 'add')
    {
        $data = request()->validate([
            'periode' => 'required',
            'periodeId' => 'nullable'
        ]);

        $periode = explode(' - ', $data['periode']);
        $periode_mulai = date('Y-m-d', strtotime($periode[0]));
        $periode_akhir = date('Y-m-d', strtotime($periode[1]));

        if (($periode_mulai > $periode_akhir) || (PeriodePengajuan::where('periode_mulai', '<=', $periode_akhir)->where('periode_akhir', '>=', $periode_mulai)->exists()) || ($mode == 'update' && $data['periodeId'] == null)) {
            session()->flash('error', 'Periode pengajuan tidak valid');
            return redirect()->back();
        }

        if ($mode == 'update') {
            $periode = PeriodePengajuan::find($data['periodeId']);
            $periode->update([
                'periode_mulai' => $periode_mulai,
                'periode_akhir' => $periode_akhir
            ]);

            session()->flash('success', 'Periode pengajuan berhasil diubah');
        } else {
            PeriodePengajuan::create([
                'periode_mulai' => $periode_mulai,
                'periode_akhir' => $periode_akhir
            ]);
        }

        session()->flash('success', 'Periode pengajuan berhasil ditambahkan');

        return redirect()->back();
    }

    public function delete_PengelolaanPeriode($id)
    {
        $periode = PeriodePengajuan::find($id);
        $periode->delete();

        session()->flash('success', 'Periode pengajuan berhasil dihapus');

        return redirect()->back();
    }
}