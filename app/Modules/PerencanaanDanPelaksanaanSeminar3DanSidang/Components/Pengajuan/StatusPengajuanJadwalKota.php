<?php
namespace App\Modules\PerencanaanDanPelaksanaanSeminar3DanSidang\Components\Pengajuan;

// Test
use Illuminate\View\Component;

class StatusPengajuanJadwalKota extends Component
{
    public $status;
    public $rejectedStep; // Properti untuk menerima rejectedStep
    public $activeColor;
    public $inactiveColor;

    // Memperbarui konstruktor untuk menerima rejectedStep
    public function __construct($status, $rejectedStep = null, $activeColor = 'primary', $inactiveColor = 'secondary')
    {
        $this->status = $status;
        $this->rejectedStep = $rejectedStep;
        $this->activeColor = $activeColor;
        $this->inactiveColor = $inactiveColor;
        // dd($status);
    }

    public function render()
    {
        $statusToStep = [
            'Diajukan' => 1,
            'Pembimbing' => 2,
            'Penguji' => 3,
            'Koordinator' => 4,
            'Diterima' => 5,
            'Ditolak' => 5,
            'Belum Ada Pengajuan' => 6
        ];
    
        $currentStep = $statusToStep[$this->status] ?? 1;
        $steps = ['Diajukan', 'Pembimbing', 'Penguji', 'Koordinator', 'Status Pengajuan'];
    
        // Tentukan warna dan ikon untuk status terakhir
        $finalStatusColor = $this->inactiveColor;
        $finalStatusIcon = 'fas fa-minus-circle';
    
        if ($this->status === 'Diterima') {
            $finalStatusColor = 'success'; 
            $finalStatusIcon = 'fas fa-check-circle'; 
        } elseif ($this->status === 'Ditolak') {
            $finalStatusColor = 'danger';
            $finalStatusIcon = 'fas fa-times-circle'; 
        }

        if ($currentStep === 6) {
            return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.Components.StatusPengajuanJadwalKota', 
            [
                'currentStep' => $currentStep,
                'steps' => $steps,
                'finalStatusColor' => $finalStatusColor,
                'finalStatusIcon' => $finalStatusIcon,
                'rejectedStep' => $this->rejectedStep,
                'msg' => true
            ]);
        }
    
        return view('PerencanaanDanPelaksanaanSeminar3DanSidang.views.pengajuan.Components.StatusPengajuanJadwalKota', 
            [
                'currentStep' => $currentStep,
                'steps' => $steps,
                'finalStatusColor' => $finalStatusColor,
                'finalStatusIcon' => $finalStatusIcon,
                'rejectedStep' => $this->rejectedStep, 
                'msg' => false
            ]);
    }    
}
