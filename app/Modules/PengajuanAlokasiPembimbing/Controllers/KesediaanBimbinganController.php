<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Controllers;

use App\Models\Bidang;
use App\Models\Dosen;
use App\Models\JadwalDosenPembimbing;
use App\Models\KetertarikanBidang;
use App\Models\Kota;
use App\Models\KuotaMembimbing;
use App\Models\PeriodePengajuan;
use App\Models\Prodi;
use App\Modules\Controller;
use DB;
use Illuminate\View\View;
use Validator;

class KesediaanBimbinganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private $USER_ID = '197312271999031003';

    public function get_info(): array
    {
        $currentdate = date('Y-m-d');
        $MaxBimbingan = Prodi::leftJoin('kuota_membimbing', function ($join) {
            $join->on('prodi.id_prodi', '=', 'kuota_membimbing.id_prodi')
                ->where('kuota_membimbing.nip', $this->USER_ID);
        })
            ->select('prodi.nama_prodi', 'prodi.id_prodi', DB::raw('COALESCE(kuota_membimbing.jumlah, 0) as jumlah'))
            // ->pluck('jumlah', 'id_prodi')
            ->get()
            ->toArray();

        return [
            'BidangInterestTotal' => KetertarikanBidang::where('nip', $this->USER_ID)->count() ?: 0,
            'MaxBimbingan' => $MaxBimbingan,
            'JadwalTotal' => [
                'Day' => JadwalDosenPembimbing::where('nip', $this->USER_ID)->distinct('hari')->count() ?: 0,
                'Session' => JadwalDosenPembimbing::where('nip', $this->USER_ID)->count() ?: 0,
            ],
            'StatusBersediaMembimbing' => Dosen::where('nip', $this->USER_ID)->value('bersedia_membimbing') ?: 'tidak_bersedia',
            'Periode' => PeriodePengajuan::where('periode_mulai', '<=', $currentdate)->where('periode_akhir', '>=', $currentdate)->exists()
        ];
    }

    public function konfirmasi_kesediaan($value)
    {
        $data = [
            'nip' => $this->USER_ID,
            'bersedia_membimbing' => $value
        ];

        $validator = Validator::make($data, [
            'bersedia_membimbing' => 'required|in:bersedia,tidak_bersedia,belum_konfirmasi'
        ], [
            'bersedia_membimbing.in' => 'Status kesediaan membimbing tidak valid'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Dosen::where('nip', $data['nip'])->update([
            'bersedia_membimbing' => $data['bersedia_membimbing']
        ]);

        session()->flash('success', 'Status kesediaan membimbing berhasil diubah');

        return redirect()->back();
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
        if (!$this->get_info()['Periode']) {
            if (!$method) {
                session()->flash('error', 'Periode pengajuan belum dibuka');
                return redirect()->back();
            }
            return;
        }

        $data = request()->all();
        $data['nip'] = $this->USER_ID;
        $data['bidang'] = $data['bidang'] ?? null;

        $validator = Validator::make($data, [
            'bidang.*' => 'required|exists:bidang,id_bidang'
        ], [
            'bidang.*.exists' => 'Bidang yang dipilih tidak valid atau tidak tersedia. Input ID: ' . implode(', ', array_map(fn($id) => "bidang$id", $data['bidang'] ?? []))
        ]);

        if ($validator->fails() || !$data['bidang']) {
            if (!$data['bidang']) {
                $validator->errors()->add('bidang', 'Bidang minat tidak boleh kosong');
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            KetertarikanBidang::where('nip', $data['nip'])->delete();
            foreach ($data['bidang'] as $bidang) {
                KetertarikanBidang::create([
                    'nip' => $data['nip'],
                    'id_bidang' => $bidang
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data ketertarikan bidang');
            return redirect()->back();
        }

        session()->flash('success', 'Data ketertarikan bidang berhasil disimpan');

        if (!$method) {
            return redirect()->back();
        }
    }

    public function create_bidang()
    {
        if (!$this->get_info()['Periode']) {
            session()->flash('error', 'Periode pengajuan belum dibuka');
            return redirect()->back();
        }

        $data = request()->all();
        $data['bidang'] = ucwords(strtolower($data['bidang']));
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
            KetertarikanBidang::create([
                'nip' => $data['nip'],
                'id_bidang' => $bidang->id_bidang
            ]);
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
            'savedInformation' => $this->get_info(),
            'batas_bimbingan' => Prodi::all(),
        ];
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.JumlahMahasiswa', $data);
    }
    public function save_kuotaMahasiswa(bool $method = false)
    {
        if (!$this->get_info()['Periode']) {
            if (!$method) {
                session()->flash('error', 'Periode pengajuan belum dibuka');
                return redirect()->back();
            }
            return;
        }

        $data = request()->all();
        $data['nip'] = $this->USER_ID;

        $listProdi = Prodi::select('id_prodi')->get();
        $rules = [];
        $messages = [];
        foreach ($listProdi as $prodi) {
            $rules['val' . $prodi->id_prodi] = 'required|integer|min:0';
            $messages['val' . $prodi->id_prodi . '.integer'] = 'Kuota mahasiswa ' . $prodi->nama_prodi . ' harus berupa angka. Input ID: val' . $prodi->id_prodi;
        }
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            KuotaMembimbing::where('nip', $data['nip'])->delete();

            foreach ($listProdi as $prodi) {
                KuotaMembimbing::create([
                    'nip' => $data['nip'],
                    'id_prodi' => $prodi->id_prodi,
                    'jumlah' => $data['val' . $prodi->id_prodi]
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data kuota mahasiswa');
            return redirect()->back();
        }

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

        DB::beginTransaction();
        try {
            JadwalDosenPembimbing::where('nip', $data['nip'])->delete();
            foreach ($data['jadwals'] as $jadwal) {
                JadwalDosenPembimbing::create([
                    'nip' => $data['nip'],
                    'hari' => strtolower($jadwal['day']),
                    'jam_mulai' => $jadwal['time'][0],
                    'jam_selesai' => $jadwal['time'][1]
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan data jadwal');
            return redirect()->back();
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