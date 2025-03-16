<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Components\PengajuanPembimbing;

use Illuminate\View\Component;

class FormStepper extends Component
{
    public int $step;
    public int $currentStep;
    public string $activeColor;
    public string $inactiveColor;
    public array $hrefs;

    public function __construct(int $step = 0, int $currentStep = 0, string $activeColor = 'bg-success', string $inactiveColor = 'bg-light', array $hrefs = [])
    {
        $this->step = $step;
        $this->currentStep = $currentStep;
        $this->activeColor = $activeColor;
        $this->inactiveColor = $inactiveColor;
        $this->hrefs = $hrefs;
    }

    public function render()
    {
        return view('PengajuanAlokasiPembimbing.views.PengajuanPembimbing.Components.FormStepper');
    }
}