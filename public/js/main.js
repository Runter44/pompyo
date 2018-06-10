$(document).ready(function () {
    // INSCRIPTION
    $("#utilisateur_email").keyup(function () {
        $.ajax({
            url: '/ajax/existe-email/',
            type: 'POST',
            data: {
                email: $("#utilisateur_email").val()
            },
            success: function (data) {
                if (data !== "") {
                    $("#utilisateur_email")[0].setCustomValidity("Il existe déjà un compte avec cette adresse e-mail !");
                } else {
                    $("#utilisateur_email")[0].setCustomValidity("");
                }
            }
        });
    });

    // ARTICLES

    $("#article_miniature").change(function () {
        readURL(this, $('#apercuMiniature'), $("#nomMiniatureInput"));
        if (this.files[0].name.toLowerCase().endsWith(".jpg")) {
            readURL(this, $("#apercuMiniature"), $("#nomMiniatureInput"));
        } else {
            alert("Le fichier sélectionné n'est pas valide !");
            $("#article_miniature").val("");
            readURL(this, $("#apercuMiniature"), $("#nomMiniatureInput"));
        }
    });

    $("#imageUpload").change(function (event) {
        if (this.files[0].name.toLowerCase().endsWith(".jpg") || this.files[0].name.toLowerCase().endsWith(".png") || this.files[0].name.toLowerCase().endsWith(".gif")) {
            readURL(this, $("#apercuImageUpload"), $("#labelImageUpload"));
        } else {
            alert("Le fichier sélectionné n'est pas valide !");
            $("#imageUpload").val("");
            readURL(this, $("#apercuImageUpload"), $("#labelImageUpload"));
        }
    });

    $("#formImportImage").submit(function (event) {
        event.preventDefault();
        if ($("#imageUpload").val() === "") {
            alert("Vous n'avez pas sélectionné d'image !");
            return;
        }
        $.ajax({
            url: "/ajax/upload-image/",
            data: new FormData(this),
            type: "POST",
            contentType: false,
            processData: false,
            cache: false,
            success: function (data) {
                $('#modalAjoutImage').modal('hide');
                readURL($("#imageUpload"), $("#apercuImageUpload"), $("#labelImageUpload"));
                let imgHtml = CKEDITOR.dom.element.createFromHtml('<img src="/uploads/articles/images/' + data + '" alt="Image" style="max-height: 300px; cursor: pointer;" class="img-fluid" onclick="openModalImage(this);">');
                if (CKEDITOR.instances.article_contenu) {
                    CKEDITOR.instances.article_contenu.insertElement(imgHtml);
                } else if (CKEDITOR.instances.article_modify_contenu) {
                    CKEDITOR.instances.article_modify_contenu.insertElement(imgHtml);
                }
            }
        });
    });

    if ($("#article_contenu").length > 0) {
        CKEDITOR.replace('article_contenu', {
            language: 'fr',
            removeButtons: "Subscript,Superscript,Anchor,Styles,Cut,Copy,Paste,PasteText,PasteFromWord,Source,Maximize,Image",
            height: 600,
        });
    }

    if ($("#article_modify_contenu").length > 0) {
        CKEDITOR.replace('article_modify_contenu', {
            language: 'fr',
            removeButtons: "Subscript,Superscript,Anchor,Styles,Cut,Copy,Paste,PasteText,PasteFromWord,Source,Maximize,Image",
            height: 600,
        });
    }

    /* EVENEMENTS */
    if (!$("#evenement_inscriptionPossible").is(':checked')) {
        $("#evenement_dateLimiteInscription").hide();
        $("#evenement_dateLimiteInscription_label").hide();
    }

    $("#evenement_dateLimiteInscription").attr('max', $("#evenement_dateDebut").val());

    $("#evenement_dateDebut").change(function () {
        $("#evenement_dateLimiteInscription").attr('max', $("#evenement_dateDebut").val());
    });

    $("#evenement_inscriptionPossible").change(function () {
        if ($("#evenement_inscriptionPossible").is(':checked')) {
            $("#evenement_dateLimiteInscription").show();
            $("#evenement_dateLimiteInscription_label").show();
        } else {
            $("#evenement_dateLimiteInscription").hide();
            $("#evenement_dateLimiteInscription_label").hide();
        }
    });

    if ($("#evenement_description").length > 0) {
        CKEDITOR.replace("evenement_description", {
            language: 'fr',
            removeButtons: "Subscript,Superscript,Anchor,Styles,Cut,Copy,Paste,PasteText,PasteFromWord,Source,Maximize,Image"
        });
    }
});

function openModalImage(image) {
    $("#imageModale").show();
    $("#imgImageModale").attr('src', image.src);
    $("#imageModale").click(function (event) {
        if (!$(event.target).is($("#imgImageModale"))) {
            $("#imageModale").hide();
        }
    });
}

function readURL(input, target, label) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        label.html(input.files[0].name);

        reader.onload = function (e) {
            target.attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        label.html("Choisissez une image");
        target.attr("src", "");
    }
}

function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("tableauInscritsEvenement");
    switching = true;
    dir = "asc";
    while (switching) {
        switching = false;
        rows = table.getElementsByTagName("TR");
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            if (dir === "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir === "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount === 0 && dir === "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}

function bbcode(bbdebut, bbfin) {
    var input = window.document.article.article_contenu;
    input.focus();
    if (typeof document.selection != 'undefined') {
        var range = document.selection.createRange();
        var insText = range.text;
        range.text = bbdebut + insText + bbfin;
        range = document.selection.createRange();
        if (insText.length == 0) {
            range.move('character', -bbfin.length);
        } else {
            range.moveStart('character', bbdebut.length + insText.length + bbfin.length);
        }
        range.select();
    } else if (typeof input.selectionStart != 'undefined') {
        var start = input.selectionStart;
        var end = input.selectionEnd;
        var insText = input.value.substring(start, end);
        input.value = input.value.substr(0, start) + bbdebut + insText + bbfin + input.value.substr(end);
        var pos;
        if (insText.length == 0) {
            pos = start + bbdebut.length;
        } else {
            pos = start + bbdebut.length + insText.length + bbfin.length;
        }
        input.selectionStart = pos;
        input.selectionEnd = pos;
    } else {
        var pos;
        var re = new RegExp('^[0-9]{0,3}$');
        while (!re.test(pos)) {
            pos = prompt("insertion (0.." + input.value.length + "):", "0");
        }
        if (pos > input.value.length) {
            pos = input.value.length;
        }
        var insText = prompt("Veuillez taper le texte");
        input.value = input.value.substr(0, pos) + bbdebut + insText + bbfin + input.value.substr(pos);
    }
}
