
@if ($msg) 
    <h3 class="text-center text-danger">Belum Ada Pengajuan</h3>
@else
    <div class="row d-flex justify-content-center align-items-center">
        @foreach ($steps as $index => $step)
            @php
                $stepNumber = $index + 1;
                $isActive = $currentStep >= $stepNumber;
                $isCompleted = $currentStep > $stepNumber;
                $stepColor = $isActive ? $activeColor : $inactiveColor;
                $stepIcon = $isActive ? 'fa-check-circle' : 'fa-minus-circle';

                // Jika langkah ini adalah yang ditolak
                if ($rejectedStep !== null && $rejectedStep === $stepNumber) {
                    $stepColor = 'danger';
                    $stepIcon = 'fas fa-times-circle';
                }

                // Jika langkah ini setelah langkah yang ditolak, ubah ke inactive
                if ($rejectedStep !== null && $stepNumber > $rejectedStep) {
                    $stepColor = $inactiveColor;
                    $stepIcon = 'fas fa-minus-circle';
                }

                // Jika langkah terakhir, gunakan warna danger jika ada yang ditolak
                if ($stepNumber === count($steps)) {
                    $stepColor = $finalStatusColor;
                    $stepIcon = $finalStatusIcon;
                }
            @endphp
            
            <div class="d-flex flex-column align-items-center mx-2">
                <div 
                    class="bg-{{ $stepColor }} rounded-circle d-flex justify-content-center align-items-center" 
                    style="width: 30px; height: 30px;">
                    <i class="fas {{ $stepIcon }}" style="color: white;"></i>
                </div>
                <span class="text-center text-{{ $stepColor }} mt-2">
                    {{ $step }}
                </span>
            </div>
            
            @if ($stepNumber < count($steps))
                <div class="d-flex align-items-center">
                    <div class="step-divider" 
                        style="background-color: {{ is_numeric($rejectedStep) && $stepNumber >= $rejectedStep ? 'lightgray' : ($isCompleted ? 'green' : 'lightgray') }};">
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif

@section('css')
    <style>
        .step-divider {
            width: 60px;
            height: 2px;
        }

        @media (max-width: 767px) {
            .step-divider {
                width: 30px;
            }
        }
    </style>
@endsection
