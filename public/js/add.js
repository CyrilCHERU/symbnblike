$('#addimage').click(function() {
    // On récupère le numéro des futures images, de leur champ respectif
    const index = +$('#widgets-counter').val();
    
    // On récupère le prototype des entrées
    const tmpl = $('#ad_form_images').data('prototype').replace(/__name__/g, index);
    
    // On injecte le code au sein de la div
    $('#ad_form_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    // Gestion du boutton supprimer
    handleDeleteButtons();
});

function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function() {
        const target = this.dataset.target;
        $(target).remove();
    });
}

function updateCounter() {
    const count = +$('#ad_form_images div.form-group').length;

    $('#widgets-counter').val(count);
}
updateCounter();

handleDeleteButtons();