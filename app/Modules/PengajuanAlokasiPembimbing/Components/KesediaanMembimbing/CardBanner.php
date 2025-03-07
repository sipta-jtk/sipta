<?php

namespace App\Modules\PengajuanAlokasiPembimbing\Components\KesediaanMembimbing;

use Illuminate\View\Component;

class CardBanner extends Component
{
    public string $type;
    public string $innerHtml;
    public string $href;
    public string $hrefText;
    public string $icon;


    public function __construct(string $type = 'success', string $innerHtml = '', string $icon = 'fas fa-eye', string $href = '', string $hrefText = 'Lihat Detail')
    {
        $this->type = $type;
        $this->innerHtml = $innerHtml;
        $this->icon = $icon;
        $this->href = $href;
        $this->hrefText = $hrefText;
    }

    public function render()
    {
        return view('PengajuanAlokasiPembimbing.views.KesediaanBimbingan.Components.CardBanner');

    }
}