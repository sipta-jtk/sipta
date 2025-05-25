$(document).ready(function () {
    const editors = document.querySelectorAll('trix-editor');

    editors.forEach((editor, index) => {
        const counter = document.querySelector(`#char-count-${index}`);
        // Get publication status from data attribute
        const isPublished = editor.dataset.isPublished === 'true';

        // If feedback is published, set Trix Editor to read-only and hide toolbar
        if (isPublished) {
            editor.setAttribute('contenteditable', 'false'); // Disable direct editing
            editor.toolbar.style.display = 'none'; // Hide Trix toolbar
        }

        const updateContent = () => {
            const inputId = editor.getAttribute("input");
            const inputWithHtml = document.getElementById(inputId);
            if (inputWithHtml) {
                inputWithHtml.value = editor.innerHTML;
            }

            const plainText = editor.editor.getDocument().toString().trim();
            const wordCount = plainText.split(/\s+/).filter(word => word.length > 0).length;
            
            // Only update counter text if the counter element exists
            if (counter) {
                counter.textContent = `${wordCount} kata`;

                // Apply styling based on word count only if not published.
                // If published, the counter will remain 'text-muted'.
                if (!isPublished) {
                    if (wordCount < 30) {
                        counter.classList.add("text-danger");
                        counter.classList.remove("text-muted");
                    } else {
                        counter.classList.remove("text-danger");
                        counter.classList.add("text-muted");
                    }
                } else {
                    // Ensure it's always muted if published
                    counter.classList.remove("text-danger");
                    counter.classList.add("text-muted");
                }
            }
        };

        editor.addEventListener("trix-change", updateContent);
        // Call updateContent directly for initial status initialization for all editors
        updateContent();
    });

    window.LihatDokumen = function (filePath, dokumenId, dokumenKategori) {
        if (filePath && filePath.trim() !== '') {
            const fullUrl = `${window.location.origin}/storage/${filePath}`;
            const fileExtension = filePath.split('.').pop().toLowerCase();

            // Open in new tab
            $('#view_file_link').attr('href', fullUrl);

            // Create download URL from template using dokumenId and dokumenKategori
            const $downloadLink = $('#view_file_download');
            const urlTemplate = $downloadLink.data('url-template');

            // Only proceed if dokumenId and dokumenKategori are valid
            if (dokumenId && dokumenKategori) {
                const downloadUrl = urlTemplate
                    .replace('__kategori__', dokumenKategori)
                    .replace('__id__', dokumenId);
                $downloadLink.attr('href', downloadUrl).removeClass('disabled'); // Enable download button
            } else {
                $downloadLink.attr('href', '#').addClass('disabled'); // Disable if ID/Category is invalid
            }

            // Preview file
            if (['pdf', 'png', 'jpg', 'jpeg'].includes(fileExtension)) {
                $('#viewDocumentPreview').attr('src', fullUrl).show();
                $('#viewPreviewNotAvailable').hide();
            } else if (['doc', 'docx', 'ppt', 'pptx'].includes(fileExtension)) {
                // Use encodeURIComponent for URL to avoid issues with special characters
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
            $('#view_file_download').attr('href', '#').addClass('disabled'); // Disable download button if no file path
        }
    };
});
