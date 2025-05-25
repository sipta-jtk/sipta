<x-adminlte-modal id="myModals" title="Preferensi Notifikasi" theme="primary" icon="fas fa-bell" size="md" scrollable>
    <form id="notifForm">
        @csrf

        <p class="mb-3">Pilih metode notifikasi yang ingin Anda terima:</p>

        <!-- Email -->
        <div class="d-flex align-items-center mb-3">
            <i class="fas fa-envelope" style="font-size: 24px; width: 30px;"></i>
            <span class="switch-label flex-grow-1 ml-2">Email</span>
            <label class="switch mb-0">
                <input type="checkbox" id="emailSwitch">
                <span class="slider"></span>
            </label>
        </div>

        <!-- Reminder H-5 -->
        <div class="d-flex align-items-center mb-3">
            <i class="fas fa-bell" style="font-size: 24px; width: 30px;"></i>
            <span class="switch-label flex-grow-1 ml-2">Reminder H-5</span>
            <label class="switch mb-0">
                <input type="checkbox" id="reminderSwitch">
                <span class="slider"></span>
            </label>
        </div>

        <x-slot name="footerSlot">
            <x-adminlte-button label="Simpan" theme="primary" onclick="savePreferences()" />
        </x-slot>
    </form>
</x-adminlte-modal>

<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 50px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50px;
    }

    input:checked+.slider {
        background-color: #4CAF50;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    .switch-label {
        font-size: 18px;
    }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">


<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchPreferences();

        document.querySelector(".btn-primary").addEventListener("click", function(e) {
            e.preventDefault();
            savePreferences();
        });
    });

    function fetchPreferences() {
        fetch("{{ route('preferensi.notifikasi.get') }}", {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById("emailSwitch").checked = data.email;
                    document.getElementById("reminderSwitch").checked = data.reminder_h5;
                }
            });
    }

    function savePreferences() {
        let email = document.getElementById("emailSwitch").checked ? 1 : 0;
        let reminder = document.getElementById("reminderSwitch").checked ? 1 : 0;

        fetch("{{ route('preferensi.notifikasi.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    email: email,
                    reminder_h5: reminder
                })
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                $("#myModals").modal("hide");
            })
            .catch(error => console.error("Error:", error));
    }
</script>