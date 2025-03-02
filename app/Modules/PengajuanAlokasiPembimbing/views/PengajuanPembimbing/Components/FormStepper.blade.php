<div class="col-3">
    <div class="d-flex flex-column align-items-start" style="margin-left: 10px;">
        @for ($i = 1; $i <= $step; $i++)
            <div class="d-flex align-items-center mb-2">
                <a href="{{ $hrefs[$i - 1] }}" type="button" 
                    class="btn btn-{{ $i == $currentStep ? $activeColor : $inactiveColor }} rounded-circle d-flex justify-content-center align-items-center"
                    style="width: 30px; height: 30px;">
                    <i class="fas fa-check-circle"></i>
                </a>
                <span class="ms-2 {{ $i == $currentStep ? 'text-' . $activeColor : '' }}">
                    @if ($i == 1)
                        Data Kelompok
                    @elseif ($i == 2)
                        Topik Tugas Akhir
                    @elseif ($i == 3)
                        Prioritas Dosen<br>Pembimbing
                    @elseif ($i == 4)
                        Pratinjau
                    @endif
                </span>
            </div>

            @if ($i != $step)
                <div class="d-flex flex-column align-items-center" style="margin-left: 13px;">
                    <div class="mb-2" style="width: 2px; height: 60px; background-color: {{ $i <= $currentStep ? '#0d6efd' : 'gray' }};"></div>
                </div>
            @endif
        @endfor
    </div>
</div>
