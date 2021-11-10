/*! mAgenda 3.2
 */
var defaultLanguage = "en",
  defaultCityCode = 1000,
  defaultCityId = 616,
  defaultRange = 30;
$(document).on("mobileinit", function () {
  $.cookie("width", screen.width, { expires: 365 });
  initLanguage();
  if ($.cookie("cityid") == null) {
    initSettings();
  }
});
$(function () {
  $("div[data-role=panel]").panel().enhanceWithin();
  $("div[data-role=popup]").popup().enhanceWithin();
  $(document).on("pageshow", 'div[data-role="page"]', function () {
    $("#listview", this).on("click", "li", function (event) {
      $.get("event.php?id=" + $(this).attr("data-id"), function (data) {
        $("#event > ul").html(data);
        $("#event").listview().listview("refresh");
      });
    });
  });
  $(document).on("pageshow", 'div[data-role="page"]', function (event) {
    try {
      var pageTracker = _gat._getTracker("UA-12677950-3");
      pageTracker._trackPageview(event.target.id);
    } catch (err) {}
  });
  $(document).on("pagecreate", "#settings", function (event) {
    $("#reset", this).click(function () {
      resetSettings();
    });
    $("#save", this).click(function () {
      saveSettings();
    });
  });
  //$('#event').on('pagecreate', function (event) {
  //    $('#tag', event.currentTarget).click(function (e) { tag(e); e.preventDefault(); });
  //});
  //$('#tevents').on('pagecreate', function (event) {
  //    $('#tag', event.currentTarget).click(function (e) { tag(e); e.preventDefault(); });
  //});
  //$('#index').on('swipeleft swiperight', function (event) {
  //    var previous = event.type == "swiperight",
  //        url = $(previous ? "#previous" : "#next", jQuery.mobile.activePage);
  //    if (url.length) {
  //        $.mobile.changePage($(url).attr("href"), previous ? { reverse: true} : {});
  //    }
  //    event.preventDefault();
  //});
});
function initLanguage() {
  if ($.cookie("language") == "nl") {
    $.mobile.listview.prototype.options.filterPlaceholder = "Filteren op...";
    $.mobile.loadingMessage = "laden";
    $.mobile.pageLoadErrorMessage = "Fout bij laden pagina";
  }
}
function initSettings() {
  $.cookie("code", defaultCityCode, { expires: 365 });
  $.cookie("cityid", defaultCityId, { expires: 365 });
  $.cookie("range", defaultRange, { expires: 365 });
  $.cookie("language", defaultLanguage, { expires: 365 });

  getGeoLocation();
}
function resetSettings() {
  initSettings();

  reloadPage("settings.php");
}
function saveSettings() {
  var $ap = jQuery.mobile.activePage,
    code = $("#cities", $ap).val(),
    range = Number($.trim($("#range", $ap).val()));

  $.cookie("code", code, { expires: 365 });
  $.cookie("cityid", $("#cities :selected", $ap).attr("data-id"), {
    expires: 365,
  });
  $.cookie("range", range, { expires: 365 });
  $.cookie(
    "language",
    $("input:radio[name=language]:checked", $.mobile.activePage).val(),
    { expires: 365 }
  );

  reloadPage("settings.php");
}
function reloadPage(url) {
  document.location.href = url;
}
function getGeoLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
      timeout: 10000,
    });
  } else {
    noLocation();
  }
  function successCallback(position) {
    foundLocation(position.coords.latitude, position.coords.longitude);
  }
  function errorCallback(error) {
    noLocation();
  }
}
function noLocation() {
  $.ajax({
    url: "http://www.geoplugin.net/json.gp?jsoncallback=?",
    dataType: "json",
    async: false,
    success: function (position) {
      foundLocation(position.geoplugin_latitude, position.geoplugin_longitude);
    },
  });
}
function foundLocation(latitude, longitude) {
  $.ajax({
    url: "jsongeocode.php",
    data: { latitude: latitude, longitude: longitude },
    dataType: "json",
    async: false,
    success: function (data) {
      $.cookie("code", data.code, { expires: 365 });
      $.cookie("cityid", data.cityid, { expires: 365 });
    },
  });
}
//function tag(event) {
//    var name = $(event.target).attr('data-name');
//    $.ajax({ url: 'jsontag.php',
//        data: { name: name },
//        dataType: 'json',
//        async: false,
//        success: function(isAdded) {
//            $(event.target).attr({
//                title: isAdded ? 'OK!': 'Hmmm',
//                'class': ('ui-btn ui-btn-icon-notext ui-icon-' + (isAdded ? 'check': 'delete'))
//            });
//        }
//    });
//}
