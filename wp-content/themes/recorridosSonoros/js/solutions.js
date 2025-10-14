/**
 * Bug Fix
 * Libreria de soluciones
 *
 * v1.0.0
 */

/**
 * Objeto de configuraciones Default.
 * Se debe modificar con la ruta completa, que sera utilizada en el sitio final.
 *
 */
const defaults = {
  path: "/",
};

jQuery(function($) {
$(document).ready(function () {
  $("#language-list").hide();

  var site = window.location.href;
  var ul = document
    .getElementById("language-list")
    .getElementsByTagName("ul")[0];
  var items = ul.getElementsByTagName("li");
  for (var i = 0; i < items.length; ++i) {
    lg = items[i].getElementsByTagName("a")[0].getAttribute("lang");
    path = items[i].getElementsByTagName("a")[0].getAttribute("href");

    if (path == site) {
      if (lg == "es-CL") {
        document.getElementById("ES").selected = "true";
        window.localStorage.setItem("langSelected", "ES");
      }
      if (lg == "en-US") {
        document.getElementById("EN").selected = "true";
        window.localStorage.setItem("langSelected", "EN");
      }
      if (lg == "fr-FR") {
        document.getElementById("FR").selected = "true";
        window.localStorage.setItem("langSelected", "FR");
      }
      if (lg == "pt-BR") {
        document.getElementById("PR").selected = "true";
        window.localStorage.setItem("langSelected", "PR");
      }
    }
  }
  var value = window.localStorage.getItem("langSelected");
  $("#LangSelector option[value='" + value + "']").prop("selected", "selected");
});
});

var lg = "";
var path = "";

var esPath = "";
var enPath = "";
var frPath = "";
var prPAth = "";

var site = window.location.href;
var ul = document.getElementById("language-list").getElementsByTagName("ul")[0];
var items = ul.getElementsByTagName("li");
for (var i = 0; i < items.length; ++i) {
  lg = items[i].getElementsByTagName("a")[0].getAttribute("lang");
  path = items[i].getElementsByTagName("a")[0].getAttribute("href");

  if (path == site) {
    if (lg == "es-CL") {
      document.getElementById("ES").selected = "true";
      window.localStorage.setItem("langSelected", "ES");
    }
    if (lg == "en-US") {
      document.getElementById("EN").selected = "true";
      window.localStorage.setItem("langSelected", "EN");
    }
    if (lg == "fr-FR") {
      document.getElementById("FR").selected = "true";
      window.localStorage.setItem("langSelected", "FR");
    }
    if (lg == "pt-BR") {
      document.getElementById("PR").selected = "true";
      window.localStorage.setItem("langSelected", "PR");
    }
  }

  if (lg == "es-CL") {
    esPath = path;
  }
  if (lg == "en-US") {
    enPath = path;
  }
  if (lg == "fr-FR") {
    frPath = path;
  }
  if (lg == "pt-BR") {
    prPath = path;
  }
}

function onChangeLang(value) {
  console.log(value);
  const urlParams = new URLSearchParams(window.location.search);
  const activateRecorridoSnoro = urlParams.get("rt");
  const activateButtonRecorridoSonoro = urlParams.get("pb");
  var str = "";
  if (activateRecorridoSnoro && activateRecorridoSnoro != "") {
    str = "?rt=" + activateRecorridoSnoro;
    console.log(str);
  }
  if (
    activateButtonRecorridoSonoro &&
    activateButtonRecorridoSonoro != "" &&
    localStorage.getItem("estabaPresionado")
  ) {
    if (localStorage.getItem("estabaPresionado") == 1) {
      str = "?pb=" + 1;
      console.log(str);
    } else {
      str = "?pb=" + 0;
      console.log(str);
    }
  }

  if (value == "ES") {
    window.localStorage.setItem("langSelected", "ES");
    window.location.href = esPath + str;
  } else if (value == "EN") {
    window.localStorage.setItem("langSelected", "EN");
    window.location.href = enPath + str;
  } else if (value == "FR") {
    window.localStorage.setItem("langSelected", "FR");
    window.location.href = frPath + str;
  } else if (value == "PR") {
    window.localStorage.setItem("langSelected", "PR");
    window.location.href = prPath + str;
  }
}

/**
 * Funcion para cambiar las urls de los museos presentes en recorrido sonoro
 * de forma dinamica y agregar el nuevo parametro.
 */
jQuery(function($) {
$(document).ready(function () {
  //Se obtiene parametro de la url
  const urlParams = new URLSearchParams(window.location.search);
  const activateRecorridoSnoro = urlParams.get("rt");
  var str = "";
  if (activateRecorridoSnoro != "") {
    str = "?rt=" + activateRecorridoSnoro;
    console.log(str);
  }

  // Si el parametro existe y existe el div que contiene los museos, se realiza
  // la funcionalidad
  if (urlParams.get("rt") && $(".imagenes-catalogo")) {
    // Se obtienen todos los museos presentes en la pagina recorridos sonoros
    let museos_links = $(".imagenes-catalogo").children();

    //Se recorren los hijos y se asigna el parametro a la url
    for (var i = 0; i < museos_links.length; i++) {
      var child = $(".imagenes-catalogo").children()[i];
      let hrefOriginal = $(child).children("a").attr("href");
      //console.log("href original "+hrefOriginal);
      $(child)
        .children("a")
        .attr("href", hrefOriginal + str);
      //console.log("href luego del cambio "+$(child).children("a").attr("href"));
    }
  } else {
    console.log(
      "No cumple las condiciones para reemplazo en recorrido sonoro..."
    );
  }
});
});

/*
 *Funcion que permite traducir los textos del museo en los diferentes idiomas, dependiendo del idioma general seleccionado
 *
 */
function languageDictionary(langSelected) {
  switch (langSelected) {
    case "ES":
      console.log(
        "No se realizan traducciones, ya se encuentran en español..."
      );
      break;
    case "PR":
      if ($("#searchTitle")) {
        $("#searchTitle").html("Resultados:");
      }

      if ($("#notFoundMessage")) {
        $("#notFoundMessage").html("Nenhum resultado encontrado.");
      }
      if ($("#textTituloRecorridoSonoro")) {
        $("#textTituloRecorridoSonoro").html("Tour Som");
      }
      if ($("#titulo1")) {
        //$("#titulo1").html();
      }
      if ($("#buttonTranscripcionCompleta")) {
        $("#buttonTranscripcionCompleta").html("Veja a transcrição completa");
      }
      if ($("#titulo2")) {
        //$("#titulo2").html();
      }
      if ($("#textTituloComparte")) {
        $("#textTituloComparte").html("Compartilhe");
      }
      if ($("#buttonTranscripcionCompleta2")) {
        $("#buttonTranscripcionCompleta2").html("Veja a transcrição completa");
      }
      if ($("#recorridoBtn")) {
        $("#recorridoBtn").html("Veja toda a tour de som");
      }
      if ($("#textTituloRecorridoVirtual")) {
        $("#textTituloRecorridoVirtual").html("Tour Virtual");
      }
      if ($("#textTituloComparte2")) {
        $("#textTituloComparte2").html("Compartilhe");
      }
      if ($("#textVerMasRecorrido")) {
        $("#textVerMasRecorrido").html("Veja mais sobre este tour");
      }
      if ($("#textTituloSobreMuseo")) {
        $("#textTituloSobreMuseo").html("Sobre o museu");
      }
      if ($("#textTituloComparte3")) {
        $("#textTituloComparte3").html("Compartilhe");
      }

      if ($("#textTituloComparte5")) {
        $("#textTituloComparte5").html("Compartilhe");
      }

      if ($("#textRecorridoSonoro")) {
        $("#textRecorridoSonoro").html("Tour Som");
      }
      if ($("#titulo0")) {
        //($("#titulo0").html("");
      }
      if ($("#textTituloComparte4")) {
        $("#textTituloComparte4").html("Compartilhe");
      }
      if ($("#textTituloListaSonidos")) {
        $("#textTituloListaSonidos").html("Lista de sons");
      }
      if ($("#textTituloAudio1")) {
        //$("#textTituloAudio1").html();
      }
      if ($("#textTituloAudio2")) {
        //$("#textTituloAudio2").html();
      }
      if ($("#textTituloAudio3")) {
        //$("#textTituloAudio3").html();
      }
      if ($("#textTituloVisitaPresencialMuseo")) {
        $("#textTituloVisitaPresencialMuseo").html(
          "Para a sua visita ao museu"
        );
      }
      break;
    case "FR":
      if ($("#searchTitle")) {
        $("#searchTitle").html("Résultats:");
      }

      if ($("#notFoundMessage")) {
        $("#notFoundMessage").html("Aucun résultat trouvé.");
      }

      if ($("#textTituloRecorridoSonoro")) {
        $("#textTituloRecorridoSonoro").html("Visite Sonore");
      }
      if ($("#titulo1")) {
        //$("#titulo1").html();
      }
      if ($("#buttonTranscripcionCompleta")) {
        $("#buttonTranscripcionCompleta").html(
          "Voir la transcription complète"
        );
      }
      if ($("#titulo2")) {
        //$("#titulo2").html();
      }
      if ($("#textTituloComparte")) {
        $("#textTituloComparte").html("Partager");
      }
      if ($("#buttonTranscripcionCompleta2")) {
        $("#buttonTranscripcionCompleta2").html(
          "Voir la transcription complète"
        );
      }
      if ($("#recorridoBtn")) {
        $("#recorridoBtn").html("Voir toute le visite sonore");
      }
      if ($("#textTituloRecorridoVirtual")) {
        $("#textTituloRecorridoVirtual").html("Tour virtuel");
      }
      if ($("#textTituloComparte2")) {
        $("#textTituloComparte2").html("Partager");
      }
      if ($("#textVerMasRecorrido")) {
        $("#textVerMasRecorrido").html("En savoir plus sur cette visite");
      }
      if ($("#textTituloSobreMuseo")) {
        $("#textTituloSobreMuseo").html("À propos du musée");
      }
      if ($("#textTituloComparte3")) {
        $("#textTituloComparte3").html("Partager");
      }

      if ($("#textTituloComparte5")) {
        $("#textTituloComparte5").html("Partager");
      }

      if ($("#textRecorridoSonoro")) {
        $("#textRecorridoSonoro").html("Visite Sonore");
      }
      if ($("#titulo0")) {
        //($("#titulo0").html("");
      }
      if ($("#textTituloComparte4")) {
        $("#textTituloComparte4").html("Partager");
      }
      if ($("#textTituloListaSonidos")) {
        $("#textTituloListaSonidos").html("Liste des sons");
      }
      if ($("#textTituloAudio1")) {
        //$("#textTituloAudio1").html();
      }
      if ($("#textTituloAudio2")) {
        //$("#textTituloAudio2").html();
      }
      if ($("#textTituloAudio3")) {
        //$("#textTituloAudio3").html();
      }
      if ($("#textTituloVisitaPresencialMuseo")) {
        $("#textTituloVisitaPresencialMuseo").html(
          "Pour votre visite au musée"
        );
      }
      break;
    case "EN":
      if ($("#searchTitle")) {
        $("#searchTitle").html("Results:");
      }

      if ($("#notFoundMessage")) {
        $("#notFoundMessage").html("No results found.");
      }

      if ($("#textTituloRecorridoSonoro")) {
        $("#textTituloRecorridoSonoro").html("Sound Tour");
      }
      if ($("#titulo1")) {
        //$("#titulo1").html();
      }
      if ($("#buttonTranscripcionCompleta")) {
        $("#buttonTranscripcionCompleta").html("See full transcript");
      }
      if ($("#titulo2")) {
        //$("#titulo2").html();
      }
      if ($("#textTituloComparte")) {
        $("#textTituloComparte").html("Share");
      }
      if ($("#buttonTranscripcionCompleta2")) {
        $("#buttonTranscripcionCompleta2").html("See full transcript");
      }
      if ($("#recorridoBtn")) {
        $("#recorridoBtn").html("See all sound tour");
      }
      if ($("#textTituloRecorridoVirtual")) {
        $("#textTituloRecorridoVirtual").html("Virtual Tour");
      }
      if ($("#textTituloComparte2")) {
        $("#textTituloComparte2").html("Share");
      }
      if ($("#textVerMasRecorrido")) {
        $("#textVerMasRecorrido").html("See more about this tour");
      }
      if ($("#textTituloSobreMuseo")) {
        $("#textTituloSobreMuseo").html("About the museum");
      }
      if ($("#textTituloComparte3")) {
        $("#textTituloComparte3").html("Share");
      }

      if ($("#textTituloComparte5")) {
        $("#textTituloComparte5").html("Share");
      }

      if ($("#textRecorridoSonoro")) {
        $("#textRecorridoSonoro").html("Sound Tour");
      }
      if ($("#titulo0")) {
        //($("#titulo0").html("");
      }
      if ($("#textTituloComparte4")) {
        $("#textTituloComparte4").html("Share");
      }
      if ($("#textTituloListaSonidos")) {
        $("#textTituloListaSonidos").html("Sound List");
      }
      if ($("#textTituloAudio1")) {
        //$("#textTituloAudio1").html();
      }
      if ($("#textTituloAudio2")) {
        //$("#textTituloAudio2").html();
      }
      if ($("#textTituloAudio3")) {
        //$("#textTituloAudio3").html();
      }
      if ($("#textTituloVisitaPresencialMuseo")) {
        $("#textTituloVisitaPresencialMuseo").html(
          "For your visit to the museum"
        );
      }
      break;
  }
}

jQuery(function($) {
$(document).ready(() => {
  var langSelected = window.localStorage.getItem("langSelected");
  console.log("Lenguaje seleccionado : " + langSelected);
  languageDictionary(langSelected);
});
});

/**Bug Fix 2221 */
function searchPost(event) {
  event.preventDefault();
  var pagina = setPathSearch();
  var path = window.location.origin + pagina;
  console.log(path);
  let toFind = $("#buscador").val();
  console.log("buscar :" + toFind);

  let params = new URLSearchParams({
    search: toFind,
  });

  window.location.href = path + "?" + params;
}

function searchPostMobile(event) {
  event.preventDefault();
  var pagina = setPathSearch();
  var path = window.location.origin + pagina;
  console.log(path);
  let toFind = $("#buscador-mob").val();
  console.log("buscar :" + toFind);

  let params = new URLSearchParams({
    search: toFind,
  });

  window.location.href = path + "?" + params;
}

function setPathSearch() {
  var langSelected = localStorage.getItem("langSelected");
  if (langSelected == "ES") {
    var pagina = defaults.path + "/busqueda";
  } else if (langSelected == "EN") {
    var pagina = defaults.path + "/search";
  } else if (langSelected == "FR") {
    var pagina = defaults.path + "/recherche";
  } else if (langSelected == "PR") {
    var pagina = defaults.path + "/pesquisa";
  }

  return pagina;
}

//Desarrollo Enter en buscador.

jQuery(function($) {
$("#buscador").focus(function () {
  $(this).data("hasfocus", true);
});

$("#buscador").blur(function () {
  $(this).data("hasfocus", false);
});

$(document.body).keyup(function (ev) {
  // 13 is ENTER
  if (ev.which === 13 && $("#buscador").data("hasfocus")) {
    searchPost(ev);
  }
});
});
