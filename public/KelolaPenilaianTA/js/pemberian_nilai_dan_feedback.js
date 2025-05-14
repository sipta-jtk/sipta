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
            const charLength = plainText.length;
            counter.textContent = `${charLength}/100 karakter`;

            if (charLength < 15 || charLength > 100) {
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

    window.LihatDokumen = function (filePath) {
        if (filePath && filePath.trim() !== '') {
            var fullUrl = `${window.location.origin}/storage/${filePath}`;
            var fileExtension = filePath.split('.').pop().toLowerCase();

            if (['pdf', 'png', 'jpg', 'jpeg'].includes(fileExtension)) {
                $('#viewDocumentPreview').attr('src', fullUrl).show();
                $('#viewPreviewNotAvailable').hide();
            } else if (['doc', 'docx', 'ppt', 'pptx'].includes(fileExtension)) {
                var viewerUrl = `https://docs.google.com/gview?url=${location.origin}${fullUrl}&embedded=true`;
                $('#viewDocumentPreview').attr('src', viewerUrl).show();
                $('#viewPreviewNotAvailable').hide();
            } else {
                $('#viewDocumentPreview').hide();
                $('#viewPreviewNotAvailable').show();
            }

            $('#LihatDokumen').modal('show');
        } else {
            $('#viewDocumentPreview').hide();
            $('#viewPreviewNotAvailable').show();
            $('#LihatDokumen').modal('show');
        }
    };
});
