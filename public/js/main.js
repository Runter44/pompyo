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

function bbcode(bbdebut, bbfin) {
  var input = window.document.article.article_contenu;
  input.focus();
  if(typeof document.selection != 'undefined'){
    var range = document.selection.createRange();
    var insText = range.text;
    range.text = bbdebut + insText + bbfin;
    range = document.selection.createRange();
    if (insText.length == 0){
      range.move('character', -bbfin.length);
    }else{
      range.moveStart('character', bbdebut.length + insText.length + bbfin.length);
    }
    range.select();
  }else if(typeof input.selectionStart != 'undefined'){
    var start = input.selectionStart;
    var end = input.selectionEnd;
    var insText = input.value.substring(start, end);
    input.value = input.value.substr(0, start) + bbdebut + insText + bbfin + input.value.substr(end);
    var pos;
    if (insText.length == 0){
      pos = start + bbdebut.length;
    } else {
      pos = start + bbdebut.length + insText.length + bbfin.length;
    }
    input.selectionStart = pos;
    input.selectionEnd = pos;
  } else {
    var pos;
    var re = new RegExp('^[0-9]{0,3}$');
    while(!re.test(pos))
    {
      pos = prompt("insertion (0.." + input.value.length + "):", "0");
    }
    if(pos > input.value.length) {
      pos = input.value.length;
    }
    var insText = prompt("Veuillez taper le texte");
    input.value = input.value.substr(0, pos) + bbdebut + insText + bbfin + input.value.substr(pos);
  }
}
