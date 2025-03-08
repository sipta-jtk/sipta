<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\Bidang;
use App\Models\Dosen;
use App\Models\JadwalDosenPembimbing;
use App\Models\KetertarikanBidang;
use App\Modules\Controller;
use DB;
use Illuminate\View\View;
use Validator;

class KesediaanBimbinganController extends Controller
{
    private $USER_ID = '10001';

    public function get_info(): array
    {
        return [
            'BidangInterestTotal' => KetertarikanBidang::where('nip', $this->USER_ID)->count() ?: 0,
            'MaxBimbingan' => [
                'MaxD3' => Dosen::where('nip', $this->USER_ID)->first()->maks_bimbingan_d3 ?? 0,
                'MaxD4' => Dosen::where('nip', $this->USER_ID)->first()->maks_bimbingan_d4 ?? 0,
            ],
            'JadwalTotal' => [
                'Day' => JadwalDosenPembimbing::where('nip', $this->USER_ID)->distinct('hari')->count() ?: 0,
                'Session' => JadwalDosenPembimbing::where('nip', $this->USER_ID)->count() ?: 0,
            ]
        ];
    }

    public function view_minatTopik(): View
    {
        $bidang = Bidang::all();
        $savedBidang = KetertarikanBidang::where('nip', $this->USER_ID)->get();

        $data = [
            'bidang' => $bidang,
            'savedBidang' => $savedBidang,
            'savedInformation' => $this->get_info()
        ];
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.Bidang', $data);
    }
    public function save_minatTopik(bool $method = false)
    {
        $data = request()->all();
        $data['nip'] = $this->USER_ID;
        $data['bidang'] = $data['bidang'] ?? [];

        $validator = Validator::make($data, [
            'bidang.*' => 'nullable|exists:bidang,id_bidang'
        ], [
            'bidang.*.exists' => 'Bidang yang dipilih tidak valid atau tidak tersedia. Input ID: ' . implode(', ', array_map(fn($id) => "bidang$id", $data['bidang']))
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (count($data['bidang']) > 5) {
            session()->flash('error', 'Maksimal 5 bidang yang dapat dipilih');
            return redirect()->back();
        }

        KetertarikanBidang::where('nip', $data['nip'])->delete();
        foreach ($data['bidang'] as $bidang) {
            KetertarikanBidang::create([
                'nip' => $data['nip'],
                'id_bidang' => $bidang
            ]);
        }

        session()->flash('success', 'Data ketertarikan bidang berhasil disimpan');

        if (!$method) {
            return redirect()->back();
        }
    }

    public function create_bidang()
    {
        $data = request()->all();
        $data['nip'] = $this->USER_ID;
        $validator = Validator::make($data, [
            'bidang' => 'required|string|max:255|unique:bidang,bidang'
        ], [
            'bidang.unique' => 'Bidang yang dimasukkan sudah ada'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $bidang = Bidang::create([
                'bidang' => $data['bidang']
            ]);
            if ($this->get_info()['BidangInterestTotal'] < 5) {
                KetertarikanBidang::create([
                    'nip' => $data['nip'],
                    'id_bidang' => $bidang->id_bidang
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menambahkan bidang baru');
            return redirect()->back();
        }

        session()->flash('success', 'Bidang baru berhasil ditambahkan');

        return redirect()->back();
    }

    public function view_kuotaMahasiswa(): View
    {
        $data = [
            'savedInformation' => $this->get_info()
        ];
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.JumlahMahasiswa', $data);
    }
    public function save_kuotaMahasiswa(bool $method = false)
    {
        $data = request()->all();
        $data['nip'] = $this->USER_ID;

        $validator = Validator::make($data, [
            'valD3' => 'required|integer|min:0',
            'valD4' => 'required|integer|min:0'
        ], [
            'valD3.integer' => 'Kuota mahasiswa D3 harus berupa angka. Input ID: valD3',
            'valD4.integer' => 'Kuota mahasiswa D4 harus berupa angka. Input ID: valD4',
            'valD3.min' => 'Kuota mahasiswa D3 tidak boleh kurang dari 0. Input ID: valD3',
            'valD4.min' => 'Kuota mahasiswa D4 tidak boleh kurang dari 0. Input ID: valD4'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Dosen::where('nip', $data['nip'])->update([
            'maks_bimbingan_d3' => $data['valD3'],
            'maks_bimbingan_d4' => $data['valD4']
        ]);

        session()->flash('success', 'Data kuota mahasiswa berhasil disimpan');

        if (!$method) {
            return redirect()->back();
        }
    }

    public function view_jadwal(): View
    {
        $data = [
            'savedInformation' => $this->get_info(),
            'jadwal' => JadwalDosenPembimbing::where('nip', $this->USER_ID)->get()
        ];
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.Jadwal', $data);
    }

    private function is_valid($jadwalData)
    {
        $previousData = [];

        foreach ($jadwalData as $jadwal) {
            $day = $jadwal['day'];
            $timeA = $jadwal['time'][0];
            $timeB = $jadwal['time'][1];

            $day = ucfirst($day);
            $timeAHour = (int) explode(':', $timeA)[0];
            $timeBHour = (int) explode(':', $timeB)[0];

            $valid = true;
            foreach ($previousData as $previous) {
                if ($previous['day'] == $day) {
                    $jadwalAHour = (int) explode(':', $previous['time'][0])[0];
                    $jadwalBHour = (int) explode(':', $previous['time'][1])[0];

                    if (
                        ($timeAHour >= $jadwalAHour && $timeAHour <= $jadwalBHour) ||
                        ($timeBHour >= $jadwalAHour && $timeBHour <= $jadwalBHour) ||
                        ($timeAHour <= $jadwalAHour && $timeBHour >= $jadwalBHour)
                    ) {
                        $valid = false;
                    }
                }
            }

            if (!$valid) {
                return false;
            }

            $previousData[] = $jadwal;
        }

        return true;
    }


    public function save_jadwal(bool $method = false)
    {
        $data = request()->all();
        $data['nip'] = $this->USER_ID;
        $data['jadwals'] = json_decode($data['jadwals'] ?? '[]', true);

        if (!$this->is_valid($data['jadwals'])) {
            session()->flash('error', 'Jadwal yang dimasukkan tidak valid');
            return redirect()->back();
        }

        JadwalDosenPembimbing::where('nip', $data['nip'])->delete();
        foreach ($data['jadwals'] as $jadwal) {
            JadwalDosenPembimbing::create([
                'nip' => $data['nip'],
                'hari' => strtolower($jadwal['day']),
                'jam_mulai' => $jadwal['time'][0],
                'jam_selesai' => $jadwal['time'][1]
            ]);
        }

        session()->flash('success', 'Data jadwal berhasil disimpan');

        if (!$method) {
            return redirect()->back();
        }
    }

    public function next_page($sourcePage = 1, $targetPage = 1)
    {
        $sourcePage = (int) $sourcePage;
        $targetPage = (int) $targetPage;

        $previous_page = NULL;
        match ($sourcePage) {
            1 => $previous_page = $this->save_minatTopik(true),
            2 => $previous_page = $this->save_kuotaMahasiswa(true),
            3 => $previous_page = $this->save_jadwal(true),
        };
        if ($previous_page) {
            return $previous_page;
        }

        $next_page = NULL;
        match ($targetPage) {
            1 => $next_page = route('pengajuanalokasipembimbing.kesediaan-membimbing.minat-bidang.index'),
            2 => $next_page = route('pengajuanalokasipembimbing.kesediaan-membimbing.jumlah-mahasiswa.index'),
            3 => $next_page = route('pengajuanalokasipembimbing.kesediaan-membimbing.jadwal.index'),
        };
        if ($next_page) {
            return redirect($next_page);
        }
        session()->flash('error', 'Halaman tidak valid');
        return redirect()->back();
    }
}