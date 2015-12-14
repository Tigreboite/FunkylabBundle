
var table; // Datatable variable


function updateModalBtn()
{
  // Support for AJAX loaded modal window.
  // Focuses on first input textbox after it loads the window.
  $('[data-toggle="modal"]').unbind();
  $('[data-toggle="modal"]').click(function(e) {
    e.preventDefault();
    loadingModal.showPleaseWait();

    if($('#modal-ajax').length)
      $('#modal-ajax').modal('hide');

    var url = $(this).data('url');

    var id = $(this).data('id') ? $(this).data('id') : 'modal-ajax';

    if (url.indexOf('#') == 0) {
      $(url).modal('open');

    } else {
      $.get(url, function(data) {
        var _html = '' +
            //'<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">' +
          '<div id="'+id+'" class="modal fade" role="dialog" aria-hidden="true">' +
          ' <div class="modal-dialog modal-lg">' +
          '   <div class="modal-content">'+ data + '</div>' +
          ' </div>' +
          '</div>';

        $(_html)
          .modal()
          .on('shown.bs.modal', function(){
            loadingModal.hidePleaseWait();
          })
          .on('hidden.bs.modal', function(){
            $(this).remove();
          })
        ;

        setTimeout(function()
        {
          //$('input:visible:first').focus();

          $('#'+id+' form').on('submit',function(e)
          {
            e.preventDefault();
            loadingModal.showPleaseWait();
            $('#'+id).addClass('hide');

            var postData = $(this).serializeArray();
            var formURL  = $(this).attr("action");

            $.ajax({
              url : formURL,
              type: "POST",
              data : postData,
              complete:function(data, textStatus, jqXHR)
              {
                $('#'+id).modal('hide');
                loadingModal.hidePleaseWait();
                if(callBackAfterSubmit && typeof callBackAfterSubmit  == "function")
                {
                  callBackAfterSubmit();
                }
                var _div = $('#'+id);
                setTimeout(function()
                {
                  _div.remove();
                },1000)

              },
              error: function(jqXHR, textStatus, errorThrown)
              {
                //console.log(textStatus);
              }
            });

            return false;

          });
        },500);

      })
        .error(function() {})
        .success(function() {});
    }
  });
  $('[data-role="ajax-request"]').unbind();
  $('[data-role="ajax-request"]').click(function(e) {
    e.preventDefault();
    var _this = $(this);
    $.post($(this).data('url'),function(data)
    {
      var callback = _this.data('callback');
      eval(callback);
    });

  });
}

function loadEditor(id, entity)
{
  var editor = CKEDITOR.instances[id];

  if(editor)
  {
    CKEDITOR.remove(editor);
  }

  if(entity == 'idea') {
    CKEDITOR.replace( id, {
      toolbar: [
        { name: 'document', items: [ ] }
      ]
    });
  }
  else if (entity == 'blog' || entity == 'faq') {

    CKEDITOR.replace( id, {
      toolbar: [
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
      ]
    });

  }
}

function setDeleteButton()
{
  // Remove btn
  $('#table_admin tbody .btn-remove' ).on( 'click', function (e) {
    e.preventDefault();
    var route = $(this).data('url');
    var data = table.row( $(this).parents('tr') ).data();
    $.confirm({
      text: "Are you sure you want to delete ?",
      confirm: function(button) {
        $.ajax({
          type: "DELETE",
          url: route,
          success: function(msg){
            table.draw();
          }
        });
      },
      cancel: function(button) {
        // do something
      }
    });

  });
}

var loadingModal;
$('document').ready(function()
{
  var html = '<div id="loaderModal" class="modal fade" data-backdrop="static" data-keyboard="false" aria-hidden="true">' +
    ' <div class="modal-dialog">' +
    '   <div class="modal-content">' +
    ' <div class="modal-header">' +
    '   <h1>Processing...</h1>' +
    ' </div>' +
    ' <div class="modal-body">' +
    '   <div class="progress">' +
    '     <div class="progress-bar progress-bar-info progress-bar-striped active" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 100%">' +
    '       <span class="sr-only">Processing</span>' +
    '     </div>' +
    '   </div>' +
    '</div>' +
    ' </div>' +
    '</div>';

  loadingModal = loadingModal || (function () {
    var pleaseWaitDiv = $(html);
    return {
      showPleaseWait: function() {
        pleaseWaitDiv.modal();
      },
      hidePleaseWait: function () {
        pleaseWaitDiv.modal('hide');
      },

    };
  })();


  updateModalBtn();
});


$('document').ready(function()
{
  $(".sidebar-toggle").on('click',function(e)
  {
    e.preventDefault();
    if($('body').hasClass('sidebar-collapse'))
    {
      $.removeCookie("sidebar_collasped",{
        path    : '/'
      });
      $('body').removeClass('sidebar-collapse');
    }else{
      $.cookie("sidebar_collasped", 1,{
        path    : '/'
      });
      $('body').addClass('sidebar-collapse');
    }
  });
  updateModalBtn();
});


