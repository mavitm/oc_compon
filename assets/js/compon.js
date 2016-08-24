function componEventRefresh()
{
   $(".aliasClick").off("click");
   $(".aliasClick").on("click",function(){
      var elem = $(this).attr("data-zopper");
      $(elem).click();
   });

   $(".aliasClickHref").off("click");
   $(".aliasClickHref").on("click",function(){
      var elem = $($(this).attr("data-zopper")).attr("href");
      window.location.href = elem;
   });
}
$(window).on('load',function() {
   $(document).ajaxComplete(function() { componEventRefresh(); });
   componEventRefresh();
});