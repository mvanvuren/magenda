/*! mAgenda 3.2
 */
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
});
