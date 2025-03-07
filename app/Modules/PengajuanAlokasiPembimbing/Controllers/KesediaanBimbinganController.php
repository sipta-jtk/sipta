<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\Bidang;
use App\Models\KetertarikanBidang;
use App\Modules\Controller;
use Illuminate\View\View;
use Validator;

class KesediaanBimbinganController extends Controller
{
    private $USER_ID = '1987654321';

    public function view_minatTopik(): View
    {
        $bidang = Bidang::all();
        $savedBidang = KetertarikanBidang::where('nip', $this->USER_ID)->get();

        $data = [
            'bidang' => $bidang,
            'savedBidang' => $savedBidang
        ];
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.Bidang', $data);
    }
    public function save_minatTopik()
    {
        $data = request()->all();
        $data['nip'] = $this->USER_ID;
        if (!isset($data['bidang'])) {
            $data['bidang'] = [];
        }

        $validator = Validator::make($data, [
            'bidang.*' => 'nullable|exists:bidang,id_bidang'
        ], [
            'bidang.*.exists' => 'Bidang yang dipilih tidak valid atau tidak tersedia. Input ID: ' . implode(', ', array_map(function ($id) {
                return 'bidang' . $id;
            }, $data['bidang']))
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        KetertarikanBidang::where('nip', $data['nip'])->delete();
        foreach ($data['bidang'] as $bidang) {
            KetertarikanBidang::create([
                'nip' => $data['nip'],
                'id_bidang' => $bidang
            ]);
        }

        session()->flash('success', 'Data ketertarikan bidang berhasil disimpan');

        return redirect()->route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index');
    }
    public function view_kuotaMahasiswa(): View
    {
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.JumlahMahasiswa');
    }
    public function view_jadwal(): View
    {
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.Jadwal');
    }
}