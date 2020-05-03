$('#add_image').click(function ()
{
    //numéro de futur champs à créer
    const index = +$('#widgets-counter').val();

    //recupérer le prototype des entrées
    const tpml = $('#ad_images').data('prototype').replace(/__name__/g , index);

    // ajouter le nouveau element
    $('#ad_images').append(tpml);
    $('#widgets-counter').val(index +1 );
    //suppression
    handleDeleteButtons();
});
function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target ;
        //console.log(target);
        $(target).remove();
    });
}
function UpdateCounter() {
    const count = +$('#add_images div.form-group').length;
    $('#widgets-counter').val(count);
}
UpdateCounter();
handleDeleteButtons();

