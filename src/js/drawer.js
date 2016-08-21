$(".mdl-button__drawer-right").click(function() {
  $(".mdl-layout__drawer-right").toggleClass("drawer-right-active");
  $(".mdl-layout__obfuscator-right").toggleClass("obfuscator-right-active");
  $(".mdl-layout__header-row").toggleClass("drawer-right-active");
  $(".content-pane").toggleClass("drawer-right-active");
});

$(".mdl-layout__obfuscator-right").click(function() {
  $(".mdl-layout__drawer-right").removeClass("drawer-right-active");
  $(".mdl-layout__obfuscator-right").removeClass("obfuscator-right-active");
});
