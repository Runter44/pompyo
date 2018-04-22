$(document).ready(function() {
    // INSCRIPTION
    // Vérification du mot de passe
    $("#utilisateur_password_first").keyup(function() {
        if ($("#utilisateur_password_first").val() === $("#utilisateur_password_second").val()) {
            $("#utilisateur_password_second")[0].setCustomValidity("");
        } else {
            $("#utilisateur_password_second")[0].setCustomValidity("Les mots de passe entrés sont différents !");
        }
    });
    $("#utilisateur_password_second").keyup(function() {
        if ($("#utilisateur_password_first").val() === $("#utilisateur_password_second").val()) {
            $("#utilisateur_password_second")[0].setCustomValidity("");
        } else {
            $("#utilisateur_password_second")[0].setCustomValidity("Les mots de passe entrés sont différents !");
        }
    });

    $("#utilisateur_email").change(function() {
        $.ajax({
            url: '/ajax/existe-email/',
            type: 'POST',
            data: {
                email: $("#utilisateur_email").val()
            },
            success: function(data) {
                if (data !== "") {
                    $("#utilisateur_email")[0].setCustomValidity("Il existe déjà un compte avec cette adresse e-mail !");
                } else {
                    $("#utilisateur_email")[0].setCustomValidity("");
                }
            }
        });
    });

    $("#article_miniature").change(function() {
        readURL(this);
    });
});

function readURL(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    $("#nomMiniatureInput").html(input.files[0].name);

    reader.onload = function(e) {
      $('#apercuMiniature').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}
