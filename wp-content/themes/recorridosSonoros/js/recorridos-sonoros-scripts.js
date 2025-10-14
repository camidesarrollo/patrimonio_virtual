//console.log("audiofiles: ",audiofiles)
//console.log("titulos: ", titulos)
//console.log("textos: ",textos)


function initlanguage(idioma) {

    // Init global variables again
    playerstate = [];
    whichPlayerIsPlaying = -1;
    totalduration = 0;


    // Init track list on obj1
    initTrackList();

    // Init player on obj1
    var reproductor = obj1.getElementsByClassName('reproductor')[0];
    console.log("reproductor: ",reproductor)
    var audio = reproductor.getElementsByClassName('audio')[0];
    audio.innerHTML = '<audio id=audio0></audio>' + audio.innerHTML;
    var controles = audio.getElementsByClassName('controles');
    var control = audio.getElementsByClassName('controles')[0];
    prev_button = control.getElementsByTagName('a')[0];
    prev_button.setAttribute('onclick', 'prevtrack();');
    play_pause_button[0] = control.getElementsByTagName('a')[1];
    play_pause_button[0].setAttribute('onclick', 'playpause(0);');
    next_button = control.getElementsByTagName('a')[2];
    next_button.setAttribute('onclick', 'nexttrack();');
    time_in[0] = control.getElementsByClassName('time-in')[0];
    time_in[0].innerHTML = '0:00';
    time_out[0] = control.getElementsByClassName('time-out')[0];
    time_out[0].innerHTML = '0:00';
    range[0] = control.getElementsByClassName('form-control-range')[0];
    range[0].value = 0;
    audioplayer[0] = document.getElementById('audio0');
    console.log("audio player init languaje:",audioplayer[0] )
    audioplayer[0].onloadedmetadata = function() {
      range[0].max = this.duration;
      time_out[0].innerHTML = formatSeconds(Math.floor(this.duration));
    };
    audioplayer[0].onended = function() {
      //nexttrack();
    };
    playerstate.push(0); // 0 = paused, 1 = playing

    // Init players on main
    var main = document.getElementById('main');
    reproductores = main.getElementsByClassName('reproductor');
    var playercount = 1;
    for (let reproductor of reproductores) {
      //          var select = reproductor.getElementsByClassName('select-biblio')[0];
      var image = reproductor.getElementsByTagName('img')[0];
      console.log("image: ",image)
      if (images.length > playercount - 1) {
        var image_url_alt = images[playercount - 1][0].split('|');
        image.src = image_url_alt[0];
        if (image_url_alt.length > 1) {
          image.alt = image_url_alt[1];
        }
      } else {
        image.src = 'https://via.placeholder.com/500x500.png?text=IMAGEN';
      }
      var audio = reproductor.getElementsByClassName('audio')[0];
      audio.innerHTML = '<audio id=audio' + playercount + '></audio>' + audio.innerHTML;
      var control = audio.getElementsByClassName('controles')[0];
      play_pause_button[playercount] = control.getElementsByTagName('a')[0];
      play_pause_button[playercount].setAttribute('onclick', 'playpause(' + playercount + ');');
      time_in[playercount] = control.getElementsByClassName('time-in')[0];
      time_out[playercount] = control.getElementsByClassName('time-out')[0];
      range[playercount] = control.getElementsByClassName('form-control-range')[0];
      range[playercount].value = 0;
      audioplayer[playercount] = document.getElementById('audio' + playercount);
      audioplayer[playercount].addEventListener('loadedmetadata', audioplayer_onloadedmetadata);

      playerstate.push(0); // 0 = paused, 1 = playing
      loadlanguage(idioma, playercount);
      playercount++;
    }
    var trackcount = document.getElementById("trackcount");
    if (trackcount != undefined) {
      trackcount.innerHTML = (playercount - 1) + " pista" + (playercount > 2 ? "s" : "");
    }

};

function audioplayer_onloadedmetadata() {
    var playerindex = this.id.substr(5);
    time_out[playerindex].innerHTML = formatSeconds(Math.floor(this.duration));
    console.log('onloadedmetadata for player' + this.id + ' totalduration before=' + totalduration + ', after=' + (totalduration + this.duration) + "\n");
    totalduration = totalduration + this.duration;

    if (document.getElementById("trackduration")) document.getElementById("trackduration").innerHTML = formatSeconds(totalduration) + " minutos";

    audioplayer[playerindex].removeEventListener('loadedmetadata', audioplayer_onloadedmetadata);
  };

  function loadlanguage(idioma, playercount) {

    // Update audiofile and markers
    if (playerstate[playercount] == 1) {
      playpause(playercount);
    }
    //        var audiofile = 'wp-content/uploads/2020/10/' + replaceAccentsAndSpaces(audiofiles[idioma][playercount - 1]);
    var audiofile = audiofiles[idioma][playercount - 1];
    time_in[playercount].innerHTML = '0:00';
    time_out[playercount].innerHTML = '0:00';
    range[playercount].value = 0;
    audioplayer[playercount].innerHTML = '<source src="' + audiofile + '" type="audio/mpeg" preload="metadata">';
    audioplayer[playercount].load();

    // Update audio titles
    document.getElementById('titulo' + playercount).innerHTML = removeLeadingDash(titulos[idioma][playercount - 1]);

    // Update textos
    var descripcion = document.getElementById("texto-" + playercount);
    if (textos[idioma][playercount - 1].length <= 300) {
      descripcion.innerHTML = textos[idioma][playercount - 1];
      var showall = descripcion.nextSibling;
      if (showall.nodeName != 'A') {
        showall = showall.nextSibling;
      }
      if (showall.nodeName == 'A') {
        showall.style.display = 'none';
      }
    } else {
      descripcion.innerHTML = textos[idioma][playercount - 1].substring(0, 300) + '...';
    }
    showmore = descripcion.parentElement.getElementsByTagName('a')[0];
    showmore.style.display = '';

  };

  function playpause(index) {
    if (interval !== undefined ) {
      console.log('367')
      clearInterval(interval);
      interval = undefined;
    }
    if (playerstate[index] == 0) {
      console.log('371')
      if (whichPlayerIsPlaying != -1  ) {
        console.log('373')
        // Stop current player (pause, set plauybutton to play, set currenTime,time_in, range to 0:00)
        audioplayer[whichPlayerIsPlaying].pause();
        audioplayer[whichPlayerIsPlaying].currentTime = 0;
        play_pause_button[whichPlayerIsPlaying].getElementsByTagName('i')[0].className = 'fas fa-play-circle';
        time_in[whichPlayerIsPlaying].innerHTML = '0:00';
        time_out[whichPlayerIsPlaying].innerHTML = '0:00';
        range[whichPlayerIsPlaying].min = 0;
        range[whichPlayerIsPlaying].max = 0;
        range[whichPlayerIsPlaying].step = 0;
        range[whichPlayerIsPlaying].value = 0;
        playerstate[whichPlayerIsPlaying] = 0;
      }

      // Here we set: time_out and range.max, but that should be on event handler
      // for new media load (o algo asi)
      if(!firsttime){
        time_in[index].innerHTML = formatSeconds(Math.floor(audioplayer[index].currentTime));
        time_out[index].innerHTML = formatSeconds(Math.floor(audioplayer[index].duration));
        range[index].min = 0;
        range[index].max = audioplayer[index].duration;
        range[index].step = 0.2;
        range[index].value = audioplayer[index].currentTime;
        range[index].onchange = function() {
          audioplayer[whichPlayerIsPlaying].currentTime = this.value;
        };

        whichPlayerIsPlaying = index;
        console.log('403')
        audioplayer[index].play();

        
        interval = setInterval(timerInterval, 1000);
        
        play_pause_button[index].getElementsByTagName('i')[0].className = 'fas fa-pause-circle';
        playerstate[index] = 1;
      }else{
        firsttime=false
      }

    } else {

      console.log('414')
      audioplayer[index].pause();
      whichPlayerIsPlaying = -1;
      play_pause_button[index].getElementsByTagName('i')[0].className = 'fas fa-play-circle';
      playerstate[index] = 0;
    }
  }

  function prevtrack() {
    if (currenttrack > 0) {
      currenttrack--;
      //          next_button.disabled == false;
      //          if (currenttrack == 0) {
      //            prev_button.disabled == true;
      //          }
      changetrack(currenttrack, false);
    }
  }

  function nexttrack() {
    if (currenttrack < audiofiles[selectedlanguage].length - 1) {
      currenttrack++;
      //          prev_button.disabled == false;
      //          if (currenttrack == audiofiles[selectedlanguage].length - 1) {
      //            next_button.disabled == true;
      //          }
      changetrack(currenttrack, false);
    }
  }

  function changetrack(track, goup) {
    if (goup) {
      jQuery(function($) {
      $('html, body').animate({
              'scrollTop' : $("#obj1").position().top
          });
        });
    }
    currenttrack = track;
    var texto = document.getElementById('texto');

    texto.innerHTML = textos[selectedlanguage][track];
    var titulo = document.getElementById('titulo0');
    console.log("titulos",titulos)
    console.log("selected languahe",selectedlanguage)
    console.log("track: ", track)
    titulo.innerHTML = removeLeadingDash(titulos[selectedlanguage][track]);

    var wasplaying = false;
    if (playerstate[0] == 1) {
      playpause(0);
      wasplaying = true;
    }
    //        audioplayer[0].src = 'wp-content/uploads/2020/10/' + replaceAccentsAndSpaces(audiofiles[selectedlanguage][track]);
    audioplayer[0] = document.getElementById('audio0');
    console.log("audio player:",audioplayer[0])
    audioplayer[0].src = audiofiles[selectedlanguage][track];
    audioplayer[0].load();
    range[0].max = audioplayer[0].duration;

    var items = '';
    var indicators = '';
    if (images[track] != undefined) {
      for (i = 0; i < images[track].length; i++) {
        items += '                        <div class="carousel-item' + (i == 0 ? ' active' : '') + '">' + "\n";
        var image_url_alt = images[track][i].split('|');
        items += '                          <img src="' + image_url_alt[0] + '"';
        if (image_url_alt.length > 1) {
          items += ' alt="' + image_url_alt[1] + '"';
        }
        items += '>' + "\n";
        items += '                        </div>' + "\n";
        indicators += '                        <li data-target="#carouselExampleIndicators" data-slide-to="' + i + (i == 0 ? '" class="active' : '') + '"></li>' + "\n";
      }
      document.getElementsByClassName('carousel-inner')[0].innerHTML = items;
      document.getElementsByClassName('carousel-indicators')[0].innerHTML = indicators;
    }

    if (wasplaying || goup) {
      playpause(0);
    }
  }

  /** 
   * 08-02-2019
   * Bug Fix : Modificacion funcion selector interno de lenguaje.
   */
  function changelang(elem, section) {
    var idSelector = elem.id;
    var actualSelectorLanguage = elem.value;
    switch (section) {
      case 'title':
        if (actualSelectorLanguage == "es") {
          $("#textTituloComparte4").html("Comparte");
        } else if (actualSelectorLanguage == "en") {
          $("#textTituloComparte4").html("Share");
        } else if (actualSelectorLanguage == "fr") {
          $("#textTituloComparte4").html("Partager");
        } else if (actualSelectorLanguage == "pt") {
          $("#textTituloComparte4").html("Compartilhe");
        }
        break;
      case 'left':
        if (actualSelectorLanguage == "es") {
          $("#buttonTranscripcionCompleta").html("Ver transcripción completa");
          $("#textTituloComparte5").html("Comparte");
        } else if (actualSelectorLanguage == "en") {
          $("#buttonTranscripcionCompleta").html("See full transcript");
          $("#textTituloComparte5").html("Share");
        } else if (actualSelectorLanguage == "fr") {
          $("#buttonTranscripcionCompleta").html(
            "Voir la transcription complète"
          );
          $("#textTituloComparte5").html("Partager");
        } else if (actualSelectorLanguage == "pt") {
          $("#buttonTranscripcionCompleta").html("Veja a transcrição completa");
          $("#textTituloComparte5").html("Compartilhe");
        }
        break;

      case 'right':
        if (actualSelectorLanguage == "es") {
          $("#buttonTranscripcionCompleta2").html("Ver transcripción completa");
          $("#textTituloComparte").html("Comparte");
        } else if (actualSelectorLanguage == "en") {
          $("#buttonTranscripcionCompleta2").html("See full transcript");
          $("#textTituloComparte").html("Share");
        } else if (actualSelectorLanguage == "fr") {
          $("#buttonTranscripcionCompleta2").html(
            "Voir la transcription complète"
          );
          $("#textTituloComparte").html("Partager");
        } else if (actualSelectorLanguage == "pt") {
          $("#buttonTranscripcionCompleta2").html("Veja a transcrição completa");
          $("#textTituloComparte").html("Compartilhe");
        }
        break;

      default:
        break;
    }
    var playercount = elem.id.replace('changelang', '');
    var track = playercount - 1;
    if (playercount == '') {
      track = currenttrack;
    }



    if (audiofiles[elem.value][track] == undefined ||
      titulos[elem.value][track] == undefined ||
      textos[elem.value][track] == undefined) {
      elem.value = selectedlanguage;
      return;
    }

    selectedlanguage = elem.value;
    selectedlanguageindex = idiomas_codes.findIndex((e) => e == selectedlanguage);
    // Init track list on obj1
    initTrackList();

    if (playercount == '') {
      document.getElementById('texto').innerHTML = textos[selectedlanguage][track];
      document.getElementById('titulo0').innerHTML = removeLeadingDash(titulos[selectedlanguage][track]);
      changetrack(track, false);
    } else {
      loadlanguage(selectedlanguage, playercount);
    }
  }

  function removeLeadingDash(s) {
    let regex = /^[-\s]+/;
    let found = s.match(regex);
    if (found != undefined) {
      return s.substring(found.length);
    } else {
      return s;
    }
  }


  function initTrackList() {
    let tracklist = document.getElementById('tracklist');
    tracklist.innerHTML = '';
    let i = 0;
    let last_i = -1;
    for (let titulo of titulos[selectedlanguage]) {
      if (titulo.substring(0, 1) != '-') {
        last_i = i;
        let e = '<div id="tracklist-entry-' + i + '"" class="col-12 col-sm-12 col-lg-12 lista">' + "\n";
        e = e + '<i class="fas fa-headphones"></i>' + "\n";
        e = e + '<h6 style="cursor: pointer;" onclick="changetrack(' + i + ', true);">' + titulo + '</h6>' + "\n";
        e = e + '</div>' + "\n";
        tracklist.innerHTML = tracklist.innerHTML + e;
      } else if (last_i != -1) {
        let elem = document.getElementById('tracklist-entry-' + last_i);
        if (elem != undefined) {
          let e = '<p style="cursor: pointer;" onclick="changetrack(' + i + ', true);">' + removeLeadingDash(titulo) + '</p>' + "\n";
          elem.innerHTML = elem.innerHTML + e;
        }
      }
      i++;
    }
  }

  function timerInterval() {
    //        if (whichPlayerIsPlaying < 0) {
    //          clearInterval(interval);
    //        }
    time_in[whichPlayerIsPlaying].innerHTML = formatSeconds(Math.floor(audioplayer[whichPlayerIsPlaying].currentTime));
    range[whichPlayerIsPlaying].value = audioplayer[whichPlayerIsPlaying].currentTime;
  }

  function formatSeconds(seconds) {
    var time = Math.floor(seconds);
    var min = Math.floor(time / 60);
    var seg = time % 60;
    return '' + min + (seg < 10 ? ':0' : ':') + seg;
  }

  function replaceAccentsAndSpaces(s) {
    var r = s;
    r = r.replace(new RegExp(/[àáâãäå]/g), "a");
    r = r.replace(new RegExp(/[èéêë]/g), "e");
    r = r.replace(new RegExp(/[ìíîï]/g), "i");
    r = r.replace(new RegExp(/ñ/g), "n");
    r = r.replace(new RegExp(/[òóôõö]/g), "o");
    r = r.replace(new RegExp(/[ùúûü]/g), "u");
    r = r.replace(new RegExp(/ /g), "-");
    return r;
  };

    /**
 * Bug Fix
 * copia de la funcion anterior, a utilizar cuando exista el parametro en la url
 *
 */
    function ocultar_especial(pista) {

    //window.localStorage.setItem("estabaPresionado", 1);
    if (window.localStorage.getItem("estabaPresionado")) {
        window.localStorage.removeItem("estabaPresionado");
    }
    window.localStorage.setItem("pathActualMuseo", window.location.origin + window.location.pathname);
    /*if (!window.location.pathname.includes("/es") &&
        !window.location.pathname.includes("/fr") &&
        !window.location.pathname.includes("/en") &&
        !window.location.pathname.includes("/pt")) {
        window.localStorage.setItem("pathActualMuseo", window.location.pathname.split("/")[2]);
    } else {
        window.localStorage.setItem("pathActualMuseo", window.location.pathname.split("/")[3]);
    }*/
    //window.localStorage.setItem("pathActualMuseo", window.location.pathname);

    if (typeof selectedlanguage == 'undefined') {
        if (window.localStorage.getItem("langSelected") == "ES") {
        selectedlanguage = "es";
        } else if (window.localStorage.getItem("langSelected") == "EN") {
        selectedlanguage = "en";
        } else if (window.localStorage.getItem("langSelected") == "FR") {
        selectedlanguage = "fr";
        } else if (window.localStorage.getItem("langSelected") == "PR") {
        selectedlanguage = "pr";
        }
    }
    /**
         * Bug Fix 
         */
    console.log(selectedlanguage);
    if (selectedlanguage != undefined) {
        if (whichPlayerIsPlaying != -1) {
        playpause(whichPlayerIsPlaying);
        }

        do {
        console.log("cargando obj1....");
        
        } while (typeof document.getElementById('obj1') == 'undefined');
        

        document.getElementById('obj1').style.display = 'block';
        document.getElementById('main').style.display = 'none';
        currenttrack = 0;
        document.getElementById('changelang').value = selectedlanguage;
        document.getElementById('texto').innerHTML = textos[selectedlanguage][0];
        document.getElementById('titulo0').innerHTML = titulos[selectedlanguage][0];
        
        if(pista!==undefined){
            changetrack( pista, true);
            pista=undefined
        
        }else{
            changetrack(currenttrack, false);
        
        }

        document.getElementById('mapa-svg').setAttribute('data', mapa_svg);
        if (mapa_svg2 != '') {
        document.getElementById('mapa-svg2').setAttribute('data', mapa_svg2);
        } else {
        var elem = document.getElementById('mapa-svg2');
        elem = elem.parentNode;
        elem.parentNode.removeChild(elem);
        elem = document.getElementsByClassName('carousel-control-prev');
        for (var i = 0; i < elem.length; i++) {
            var tatara = elem[i].parentNode;
            tatara = tatara.parentNode;
            tatara = tatara.parentNode;
            tatara = tatara.parentNode;
            if (tatara.className.startsWith('container')) {
            elem[i].style.display = 'none';
            }
        }
        elem = document.getElementsByClassName('carousel-control-next');
        for (var i = 0; i < elem.length; i++) {
            var tatara = elem[i].parentNode;
            tatara = tatara.parentNode;
            tatara = tatara.parentNode;
            tatara = tatara.parentNode;
            if (tatara.className.startsWith('container')) {
            elem[i].style.display = 'none';
            }
        }
        }

    }
}

/**
 * Bug Fix
 * Funcionalidad que permite decidir si cargar o no el recorrido sonoro, en base al path actual del museo
 * Si coincide en alguno de los grupos de museos definidos, en alguno de los idiomas, se carga, sino no se ejecuta
 * esta accion.
 */
function getCurrentPaths() {
    var currentPaths = [];
    var selector = $("#language-list");
    for (var i = 0; i < selector.children().prevObject.children().children().length; i++) {
        var currentLi = selector.children().prevObject.children().children()[i];
        currentPaths.push($(currentLi).children().attr("href"));
    }

    return currentPaths;
}                  

function checkIfSamePathName(pathActual, pathAnterior) {
    console.log(pathActual);
    console.log(pathAnterior);

    var currentPaths = getCurrentPaths();
    console.log(currentPaths);

    if (pathActual == pathAnterior) {
      console.log("Path era la misma ruta anterior, recargando...");
      return true;
    }

    console.log("seteando path actual..");
    window.localStorage.setItem("pathActualMuseo", pathActual);

    if (currentPaths.includes(pathActual)) {
      console.log("path actual " + pathActual + " se encuentra en " + currentPaths);
      return true;
    } else {
      return false;
    }

  }

function svgMapInit(elm) {
    //                  console.log("svgMapInit:" + elm);
    try {
      mapsvgdoc = elm.contentDocument;
    } catch (e) {
      mapsvgdoc = elm.getSVGDocument();
    }
    //                  mapurl = elm.src || elm.data;
    var count = 1;
    for (mapa_svg_id of mapa_svg_ids) {
      let svg_elem = mapsvgdoc.getElementById(mapa_svg_id);
      if (svg_elem != undefined) {
        svg_elem.addEventListener("mousedown", svgItemMousedown, false);
      }
      count++;
    }
  }

  function svgItemMousedown(evt) {
  var index = mapa_svg_ids.indexOf(evt.target.id);
  if (index == -1) {
    index = mapa_svg_ids.indexOf(this.id);
  }
  if (index > -1) {
    changetrack(index, true);
  }
}
