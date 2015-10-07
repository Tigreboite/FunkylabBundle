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
      data: {faqcategory:application},
      type: 'GET',
      url: Routing.generate('admin_bloc_liste'),
      complete:function(d)
      {
        $('#bloc-liste-'+_application).html(d.responseText);
        $('#blocs-'+_application).slideDown();
        updateSortBloc();
        updateModalBtn();
        
      }
    });
  }
}

function saveOrder(_this)
{
  var data = $(_this).sortable('serialize');

  if($(_this).data('application'))
  {
    data+="&type=bloc";
    data+="&faqcategory="+$(_this).data('application');
  }else{
    data+="&type=application";
  }

  $.ajax({
    data: data,
    type: 'POST',
    url: Routing.generate('admin_homeslider_order'),
    complete:function(d)
    {

    }
  });
}

function updateSortBloc()
{
  $( ".sortable-bloc" )
    .sortable({
      axis: 'y',
      placeholder: "ui-state-highlight",
      update: function (event, ui) {
        saveOrder(this);
      }
    })
    .disableSelection()
  ;
}

function refreshCategoryList(id_lang)
{

  $.ajax({
    type: 'GET',
    url: Routing.generate('admin_homeslider_liste', { id_lang: id_lang }),
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
      updateSortApp();
      updateModalBtn();
      
    }
  });
  
}

function updateSortApp()
{
  $( ".sortable" )
    .sortable({
      axis: 'y',
      placeholder: "ui-state-highlight",
      update: function (event, ui) {
        saveOrder(this);
      }
    })
    .disableSelection()
  ;
}

$(function() {

  refreshCategoryList(null)

  $(window).on('resize',onResizeApp);

  $('#liste-language').change(function() {
    refreshCategoryList($(this).val())
  })

});