@if ($msg)
    <h3 class="text-center text-danger">Belum Ada Pengajuan</h3>
@else
    <div class="row d-flex justify-content-center align-items-center flex-column flex-md-row"> {{-- Changed to flex-column on mobile, flex-md-row on medium+ --}}
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

            {{-- Step Item --}}
            <div class="d-flex flex-column align-items-center mx-2 my-2 col-12 col-md-auto"> {{-- col-12 on mobile, col-md-auto on medium+ --}}
                <div
                    class="bg-{{ $stepColor }} rounded-circle d-flex justify-content-center align-items-center"
                    style="width: 30px; height: 30px;">
                    <i class="fas {{ $stepIcon }}" style="color: white;"></i>
                </div>
                <span class="text-center text-{{ $stepColor }} mt-2 step-text-label">
                    {{ $step }}
                </span>
            </div>

            {{-- Vertical Divider for Mobile --}}
            @if ($stepNumber < count($steps))
                <div class="d-flex justify-content-center d-block d-md-none"> {{-- Show only on mobile (d-block d-md-none) --}}
                    <div class="step-divider-vertical"
                        style="background-color: {{ is_numeric($rejectedStep) && $stepNumber >= $rejectedStep ? 'lightgray' : ($isCompleted ? 'green' : 'lightgray') }};">
                    </div>
                </div>
            @endif

            {{-- Horizontal Divider for Desktop --}}
            @if ($stepNumber < count($steps))
                <div class="d-none d-md-flex align-items-center"> {{-- Show only on desktop (d-none d-md-flex) --}}
                    <div class="step-divider-horizontal"
                        style="background-color: {{ is_numeric($rejectedStep) && $stepNumber >= $rejectedStep ? 'lightgray' : ($isCompleted ? 'green' : 'lightgray') }};">
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif

@section('css')
    <style>
        .step-divider-horizontal {
            width: 60px;
            height: 2px;
        }

        .step-divider-vertical {
            width: 2px;
            height: 40px; /* Height for vertical divider */
            margin: 10px 0; /* Add some vertical margin */
        }

        /* Adjust font size for step labels on smaller screens */
        @media (max-width: 767px) {
            .step-text-label {
                font-size: 0.85rem; /* Slightly smaller text for mobile */
            }
        }
    </style>
@endsection