<?php
define ('INPHP', '1');
require_once ("load.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo (APP_NAME); ?></title>
<link href="styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="typeface-0.14.js" type="text/javascript"></script>
<script src="marcelle_script_regular.typeface.js" type="text/javascript"></script>

<script type="text/javascript" language="javascript">
var hash = document.location.hash;
var stateCode = '';
var hashVal = '';
var dataSet;
var sliderElment = 4;
var pictoW = 144;
var pictoH = 165;

$(document).ready (function () {
    if (hash.length>1) {
        hashVal = hash.substring(1, hash.length);
    }
    // load external data
    $.getJSON("feed.php", function(feed){
        // populate dropdown menu
        if (feed.status == "200 OK") {
            dataSet = feed.response;
            var options = '';

            // add options
            for (var i=0; i< feed.response.length; i++) {
                var selected = '';
                if (hashVal == feed.response[i].code) {selected = 'selected="selected"';stateCode = feed.response[i].code}
                options += '<option value="'+feed.response[i].code+'" '+selected+'>'+feed.response[i].name+'</option>';
            }
            // reset select
            $("select#stateSelector").html('<option value="">Choisissez...</option>'+options);
            if (stateCode != '') {setState(stateCode);}
        }
    });
    

    $("select#stateSelector").change(function() {
      if ($(this).val() != "") {
          setState ($(this).val());
      }
    });
    $("div.close").click(function() {
        $("#payerContent").html("");
        $(this).parent().css("display", "none");
    });
    $('#feedbackForm').submit(function() {
      feedbackSend();
      return false;
    });
    $(".field").mouseup(function () {
        $(this).removeClass('error');
       return false;
    });
    $("#videos").width($("#slider1 li").width()*sliderElment);
    $("#bjslider").width(($("#slider1 li").width()*(sliderElment))+40);
    $("#slider_next").click(function() {
        // compte les occurences de li
        if ($("#slider1").width()-Math.abs($("#slider1").css('left').replace("px", ""))>$("#videos").width()+1) {
            $("#slider1").animate({"left": "-="+$("#slider1 li").width()+"px"}, "fast", function (){
                   return bjsliderControl();
            });
        }
    });
    $("#slider_prev").click(function() {
        // compte les occurences de li
        if (Math.abs($("#slider1").css('left').replace("px", "")) > 0) {
            $("#slider1").animate({"left": "+="+$("#slider1 li").width()+"px"}, "fast", function (){
                   return bjsliderControl();
            });
        }
    });
})
function bjsliderControl () {
    if (Math.abs($("#slider1").css('left').replace("px", "")) == 0) {
        $('#slider_prev').hide();
    }
    else {
        $('#slider_prev').show();
    }
    if ($("#slider1").width()-Math.abs($("#slider1").css('left').replace("px", ""))>$("#videos").width()+1) {
        $('#slider_next').show();
    }
    else {
        $('#slider_next').hide();
    }
    return true;
}

var n;
// action
function setState (code) {
    stateCode = code;
    document.location = "#"+stateCode;
    var videoHtml = '';
    
    for (n in dataSet) {
        if (dataSet[n].code == stateCode) {
            var videos = dataSet[n].videos;
            $("#state").html(dataSet[n].name.toUpperCase());
            $('#slider_prev').hide();
            $('#slider_next').hide();
            if (videos.length>0) {
                for (var i in videos) {
                    videoHtml += '<li id="'+videos[i]+'" class="preview" onclick="playVideo($(this).attr(\'id\'))"><img src="http://i2.ytimg.com/vi/'+videos[i]+'/default.jpg" alt="" /></li>';
                }
                if (videos.length>sliderElment) {
                    $('#slider_next').show();
                }
            }
            else {
                videoHtml += '<div>désolé, pas de vidéo pour l\'instant</div>';
            }
            // set image size
            $("#idemocrat").animate({"width": (pictoW*(dataSet[n].sDemocrat>20?dataSet[n].sDemocrat:20)/100)+"px", "height": (pictoH*(dataSet[n].sDemocrat>20?dataSet[n].sDemocrat:20)/100)+"px", "margin-top": ((pictoH-(pictoH*(dataSet[n].sDemocrat>20?dataSet[n].sDemocrat:20)/100))/2)+"px"}, "slow");
            $("#irepublican").animate({"width": (pictoW*(dataSet[n].sRepublican>20?dataSet[n].sRepublican:20)/100)+"px", "height": (pictoH*(dataSet[n].sRepublican>20?dataSet[n].sRepublican:20)/100)+"px", "margin-top": ((pictoH-(pictoH*(dataSet[n].sRepublican>20?dataSet[n].sRepublican:20)/100))/2)+"px"}, "slow");
            $("#iindependent").animate({"width": (pictoW*(dataSet[n].sIndependant>20?dataSet[n].sIndependant:20)/100)+"px", "height": (pictoH*(dataSet[n].sIndependant>20?dataSet[n].sIndependant:20)/100)+"px", "margin-top": ((pictoH-(pictoH*(dataSet[n].sIndependant>20?dataSet[n].sIndependant:20)/100))/2)+"px"}, "slow");
            
            var races = "";
            if (dataSet[n].race.length>0) {
                for (var id in dataSet[n].race) {
                    races += '<li><span>'+dataSet[n].race[id]+'</span></li>';
                }
            }
            $("#what ul").slideUp ('slow', function () {
                $(this).html(races);
                $(this).slideDown ('slow');
            });
            

            // set data
            var dD = formatNumbers(dataSet[n].Democrat);
            // fade out, change, fade in
            $("#fdemocrat").fadeOut('fast', function (){
                $(this).html(dD+" $")
                $(this).fadeIn('slow');
            })
            var dR = formatNumbers(dataSet[n].Republican);
            $("#frepublican").fadeOut('fast', function() {
                $(this).html(dR+" $")
                $(this).fadeIn('slow');
            })
            var dI = formatNumbers(dataSet[n].Total-dataSet[n].Republican-dataSet[n].Democrat);
            $("#findependent").fadeOut('fast', function() {
                $(this).html(dI+" $")
                $(this).fadeIn('slow');
            })
            // winner
            var mW = dataSet[n].winner;
            $("#mI").fadeOut('fast', function () {
                if (mW == 'I') {$(this).fadeIn('slow');}
            });
            $("#mD").fadeOut('fast', function () {
                if (mW == 'D') {$(this).fadeIn('slow');}
            });
            $("#mR").fadeOut('fast', function () {
                if (mW == 'R') {$(this).fadeIn('slow');}
            });

        }
    }
    $('#slider1').html(videoHtml);
    $("#slider1").animate({"left": "0px"}, "fast");
    $("#slider1").width($("#slider1 li").size()*$("#slider1 li").width());

}

function playVideo (mediaID) {
    $("#payerContent").html('<iframe class="youtube-player" type="text/html" width="560" height="453" src="http://www.youtube.com/embed/'+mediaID+'" frameborder="0"></iframe>');
    showHide ("#player", true);
}

// message
function feedbackSend () {
    // check form (validate)
    // send AJAX
    if ($('#email').val() == "" || $('#comment').val() == "" || $('#name').val() == "") {
        if ($('#email').val() == "") {$('#email').addClass('error');} else {$('#email').removeClass('error');}
        if ($('#comment').val() == "") {$('#comment').addClass('error');} else {$('#comment').removeClass('error');}
        if ($('#name').val() == "") {$('#name').addClass('error');} else {$('#name').removeClass('error');}
        showAlert('Vous devez renseigner tous les champs.');
        return false;
    }

    var serverresponse = $.ajax({
       type: "POST", url: "mailer.php",
       data: "email="+$('#email').val()+'&name='+$('#name').val()+"&comment="+$('#comment').val(),
       async: false
     }).responseText;
    var myObject = eval('(' + serverresponse + ')');

    if (myObject.status == '200 OK') {
        if (myObject.error) {showAlert('Error: '+myObject.message);}
        else {
            showAlert('Votre message a bien été envoyé, merci.');
            showHide ('#feedback', false);
            $('#feedbackForm .field').val('');
        }
    }
    else {
        showAlert('System error: could not communicate with server');
    }
    return false;
}
function showAlert (msg) {
    $('#alertmessage').html(msg);
    showHide ('#alert', true);
    return true;
}
function showHide (elmt, show) {
    if (show) {
        $(elmt).show('fast');
    }
    else {
        $(elmt).hide('fast');
    }
    return false;
}
function formatNumbers(nStr) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? ',' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ' ' + '$2');
	}
	return x1 + x2;
}
</script>
</head>

<body>
<div id="container">

    <div id="state"></div>

    <div id="stateForm">
        <div>Sélectionnez un état</div>
        <select name="stateSelector" id="stateSelector"></select>
    </div>
    <div id="header">
        <div><span>Dépenses de communication des lobbies investies dans la campagne, par parti bénéficiaire, dans cet état</span></div>
        <div id="helptag" onclick="showHide('#help', true)">Qu'est-ce que ça veut dire</div>
    </div>
    <div id="appcontent">
        <div id="what">
            <span>Elections concernées</span>
            <ul></ul>
        </div>
        <div id="viz">
            <div id="pictos">
                <div class="picto"><img src="img/picto_democrates.png" id="idemocrat"  /></div>
                <div class="picto"><img src="img/picto_republicains.png" id="irepublican" /></div>
                <div class="picto"><img src="img/picto_autres.png" id="iindependent" /></div>
            </div>
            <div id="figures">
                <div id="fdemocrat" class="label"></div>
                <div id="frepublican" class="label"></div>
                <div id="findependent" class="label"></div>
            </div>
        </div>
        <div id="contribute" onclick="showHide('#feedback', true)">
            <h2><span>Vous habitez aux Etats-Unis&nbsp;?</span></h2>
            <span>Signalez une vidéo de la campagne</span>
        </div>
        <div id="videoslab">
            <div id="caption"><span>Visionner les vidéos de campagne de cet état</span></div>
            <div id="stripcontainer">
                <div id="bjslider">
                    <div id="videos"><ul id="slider1"><li></li></ul></div>
                    <img src="img/nav_next.png" id="slider_next" width="11" height="17" />
                    <img src="img/nav_prev.png" id="slider_prev" width="11" height="17" />
                </div>
            </div>
        </div>
    </div>
    <div id="macaron">
        <div id="mR"></div>
        <div id="mD"></div>
        <div id="mI"></div>
    </div>
    <div id="footer">
        <div id="social">
            <div id="left"></div>
            <div id="middle">
                    <!-- Les outils pour partager l'APP (Facebook, Twitter, etc) -->
                    <?php include(INC_DIR."inc.share.php"); ?>
            </div>
            <div id="right"></div>
        </div>
    </div>
    <!-- les popups... -->
    <div id="player" class="popup">
        <div class="popup_bg1"></div>
        <div class="popup_bg2"></div>
        <div id="payerContent" class="content"></div>
        <div class="close"></div>
    </div>
    <div id="feedback" class="popup">
        <div class="popup_bg1"></div>
        <div class="popup_bg2"></div>
        <div class="content">
            <form id="feedbackForm" onSubmit="return false;">
                <div class="formrow">
                    <div class="formlabel"><span>votre adresse email</span></div>
                    <div class="forminput"><input name="email" id="email" value="" type="text" class="field" /></div>
                </div>
                <div class="formrow">
                    <div class="formlabel"><span>votre lien vers la vidéo</span></div>
                    <div class="forminput"><input name="name" id="name" value="" type="text" class="field" /></div>
                </div>
                <div class="formrow">
                    <div class="formlabel"><span>votre commentaire</span></div>
                    <div class="forminput"><textarea name="comment" id="comment" class="field"></textarea></div>
                </div>
                <div class="formrow">
                    <div class="formlabel"></div>
                    <div class="forminput"><input type="button" name="envoyer" id="envoyer" value="envoyer" class="submit" onClick="return feedbackSend ();" /></div>
                </div>
            </form>
        </div>
        <div class="close"></div>
    </div>
    <div id="alert" class="popup">
        <div class="popup_bg1"></div>
        <div class="popup_bg2"></div>
        <div id="alertmessage" class="content"></div>
        <div class="close"></div>
    </div>
    <div id="help" class="popup">
        <div class="popup_bg1"></div>
        <div class="popup_bg2"></div>
        <div class="content">
            <h2><span>Dépenses de campagnes indépendantes , par parti bénéficiaire</span></h2>
            <p>Depuis janvier 2010, un jugement de la Cour Suprême autorise le financement des campagnes électorales par les lobbies à travers des PAC (Comités d'Action Politique). Les sommes, dont la traçabilité n'est pas rendue obligatoire, servent majoritairement à financer les dépenses de communication des candidats sous forme de spots publicitaires en télévision et en radio, sur le web ou par courrier.</p>
            <p>Les montants indiqués ici pour les partis Démocrates et Républicains représentent le total des sommes investies par les lobbies en soutien ou en opposition aux candidats dans chaque état. Par exemple, le montant indiqué pour le parti républicain dans l'état de Californie représente les sommes investies par les lobbies en faveur de leur candidat républicain mais aussi les sommes investies contre son concurrent démocrate pour ce même scrutin. Les sommes "autres" sont celles des candidats indépendants.</p>
            <p>Pour plus de détails vous pouvez vous reporter directement vers nos sources d'information : le site de la <a href="http://fec.gov/about.shtml" target="_blank">Federal Election Commission</a> et le site de la <a href="http://reporting.sunlightfoundation.com/" target="_blank">Sunlight Foundation Reporting Group</a>  et leur enquête complète sur les dépenses des lobbies, <a href="http://reporting.sunlightfoundation.com/independent-expenditures/" target="_blank">Follow the Unlimited Money</a>.</p>
        </div>
        <div class="close"></div>
    </div>

</div>
</body>