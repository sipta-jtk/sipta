{{-- if theres an errorsm print it in html form --}}

@if ($errors->any())
    @foreach ($errors->all() as $error)
        @php
            $parts = explode('Input ID:', $error);
            $message = $parts[0];
            $inputId = isset($parts[1]) ? explode(',', trim($parts[1])) : null;
        @endphp
        @if ($inputId)
            <script>
                $(document).ready(function() {
                    toast('error', 'Gagal', '{{ $message }}');
                });
            </script>
            @foreach ($inputId as $id)
                <script>
                    $(document).ready(function() {
                        $('#{{ trim($id) }}').addClass('is-invalid');
                    });
                </script>
            @endforeach
        @endif
    @endforeach

    @php
        $lastErrorMessages = '';
    @endphp
    @foreach ($errors->all() as $error)
        @php
            $parts = explode('Input ID:', $error);
            $message = $parts[0];
            if ($lastErrorMessages == $message) {
                continue;
            }
            $lastErrorMessages = $message;
            $inputId = isset($parts[1]) ? explode(',', trim($parts[1])) : null;
        @endphp
        <script>
            $(document).ready(function() {
                var container = $('.content-wrapper').first();
                if ($('#errorContainer').length) {
                    container = $('#errorContainer');
                }

                container.append(
                    `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>`
                );
            });
        </script>
    @endforeach

@endif
