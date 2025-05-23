$(document).ready(function () {
    const editors = document.querySelectorAll('trix-editor');

    editors.forEach((editor, index) => {
        const counter = document.querySelector(`#char-count-${index}`);

        const updateContent = () => {
            const inputId = editor.getAttribute("input");
            const inputWithHtml = document.getElementById(inputId);
            if (inputWithHtml) {
                inputWithHtml.value = editor.innerHTML;
            }

            const plainText = editor.editor.getDocument().toString().trim();
            const wordCount = plainText.split(/\s+/).filter(word => word.length > 0).length;
            counter.textContent = `${wordCount} kata`;

            if (wordCount < 30) {
                counter.classList.add("text-danger");
                counter.classList.remove("text-muted");
            } else {
                counter.classList.remove("text-danger");
                counter.classList.add("text-muted");
            }
        };

        editor.addEventListener("trix-change", updateContent);
        updateContent();
    });

    window.LihatDokumen = function (filePath, dokumenId, dokumenKategori) {
        if (filePath && filePath.trim() !== '') {
            const fullUrl = `${window.location.origin}/storage/${filePath}`;
            const fileExtension = filePath.split('.').pop().toLowerCase();

            // Buka di tab baru
            $('#view_file_link').attr('href', fullUrl);

            // Buat URL download dari template menggunakan dokumenId dan dokumenKategori
            const $downloadLink = $('#view_file_download');
            const urlTemplate = $downloadLink.data('url-template');

            // Hanya lanjutkan jika dokumenId dan dokumenKategori valid
            if (dokumenId && dokumenKategori) {
                const downloadUrl = urlTemplate
                    .replace('__kategori__', dokumenKategori)
                    .replace('__id__', dokumenId);
                $downloadLink.attr('href', downloadUrl).removeClass('disabled'); // Aktifkan tombol unduh
            } else {
                $downloadLink.attr('href', '#').addClass('disabled'); // Nonaktifkan jika ID/Kategori tidak valid
            }

            // Preview file
            if (['pdf', 'png', 'jpg', 'jpeg'].includes(fileExtension)) {
                $('#viewDocumentPreview').attr('src', fullUrl).show();
                $('#viewPreviewNotAvailable').hide();
            } else if (['doc', 'docx', 'ppt', 'pptx'].includes(fileExtension)) {
                // Gunakan encodeURIComponent untuk URL agar tidak ada masalah dengan karakter khusus
                const viewerUrl = `https://docs.google.com/gview?url=${encodeURIComponent(fullUrl)}&embedded=true`;
                $('#viewDocumentPreview').attr('src', viewerUrl).show();
                $('#viewPreviewNotAvailable').hide();
            } else {
                $('#viewDocumentPreview').hide();
                $('#viewPreviewNotAvailable').show();
            }
        } else {
            $('#viewDocumentPreview').hide();
            $('#viewPreviewNotAvailable').show();
            $('#view_file_download').attr('href', '#').addClass('disabled'); // Nonaktifkan tombol unduh jika tidak ada jalur file
        }
    };
});