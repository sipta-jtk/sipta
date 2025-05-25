$(document).ready(function () {
    function capitalizeFirstLetter(text) {
        if (!text) return text;
        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    let jenisFormulirField = $("#jenisFormulir");
    if (jenisFormulirField.length) {
        let jenisFormulirValue = jenisFormulirField.val().trim();
        if (jenisFormulirValue) {
            jenisFormulirField.val(capitalizeFirstLetter(jenisFormulirValue));
        }
    }

    let jenisTAField = $("#jenisTA");
    if (jenisTAField.length) {
        let jenisTAValue = jenisTAField.val().trim();
        if (jenisTAValue) {
            jenisTAField.val(capitalizeFirstLetter(jenisTAValue));
        }
    }

});