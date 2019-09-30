$('#addimage').click(function() {
    // On récupère le numéro des futures images, de leur champ respectif
    const index = $('#ad_form_images div.form-group').length;

    // On récupère le prototype des entrées
    const tmpl = $('#ad_form_images').data('prototype');

    console.log(tmpl);
});