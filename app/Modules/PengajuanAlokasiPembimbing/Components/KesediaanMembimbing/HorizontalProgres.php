<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Components\KesediaanMembimbing;

use Illuminate\View\Component;

class HorizontalProgres extends Component
{
    public int $number;
    public int $active;
    public string $activeColor;
    public string $inactiveColor;
    public array $hrefs;


    public function __construct(int $number = 0, int $active = 0, string $activeColor = 'bg-primary', string $inactiveColor = 'bg-secondary', array $hrefs = [])
    {
        $this->number = $number;
        $this->active = $active;
        $this->activeColor = $activeColor;
        $this->inactiveColor = $inactiveColor;
        $this->hrefs = $hrefs;
    }

    public function render()
    {
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.Components.HorizontalProgress');

    }
}