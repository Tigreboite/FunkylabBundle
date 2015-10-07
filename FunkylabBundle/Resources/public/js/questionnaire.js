var onResizeApp = function()
{
  var colonneW = new Array();

  $('tr').each(function(_tr)
  {
    $(this).find('td,th').each(function(_td)
    {
      if(colonneW[_td] == undefined)
        colonneW[_td] = 0;

      if($(this).width()>colonneW[_td])
        colonneW[_td]=$(this).width();
    });
  }).each(function(_tr)
  {
    $(this).find('td,th').each(function(_td)
    {
      $(this).width(colonneW[_td]);
    });
  });
};

function refreshBlocList(application)
{
  if(application)
  {
    var _application = application;
    $.ajax({
      data: {questionnaire:application},
      type: 'GET',
      url: Routing.generate('admin_questionnairequestion_liste'),
      complete:function(d)
      {
        $('#bloc-liste-'+_application).html(d.responseText);
        $('#blocs-'+_application).slideDown();
        updateModalBtn();
      }
    });
  }
}


function refreshCategoryList()
{

  $.ajax({
    type: 'GET',
    url: Routing.generate('admin_questionnaire_liste', {'idea_id' : idea_id}),
    complete:function(d)
    {
     $('#contentSort').html(d.responseText);

      //Display blocs
      $('.toggle-bloc').click(function(e)
      {
        e.preventDefault();

        var _blocs = $('#blocs-'+$(this).data('id'));

        if(_blocs.is(":visible"))
        {
          _blocs.slideUp();
          $(this).find('i')
            .removeClass('fa-arrow-down')
            .addClass('fa-arrow-up')
          ;
        }else{
          $('a.btn-default i')
            .removeClass('fa-arrow-down')
            .addClass('fa-arrow-up')
          ;
          $('.blocs').slideUp();
          $(this).find('i').removeClass('fa-arrow-up');
          $(this).find('i').addClass('fa-arrow-down');
          refreshBlocList($(this).data('id'));
        }
      });

      onResizeApp();
      
      updateModalBtn();
    }
  });
  
}



$(function() {

  refreshCategoryList()

  $(window).on('resize',onResizeApp);

});