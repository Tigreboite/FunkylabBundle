
var table; // Datatable variable

function updateModalBtn()
{
  // Support for AJAX loaded modal window.
  // Focuses on first input textbox after it loads the window.
  $('[data-toggle="modal"]').unbind();
  $('[data-toggle="modal"]').click(function(e) {
    e.preventDefault();

    if($('#modal-ajax').length)
      $('#modal-ajax').modal('hide');

    var url = $(this).data('url');

    var id = $(this).data('id') ? $(this).data('id') : 'modal-ajax';

    if (url.indexOf('#') == 0) {

      $(url).modal('open');

    } else {
      $.get(url, function(data) {
        var _html = '' +
          '<div id="'+id+'" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">' +
          ' <div class="modal-dialog">' +
          '   <div class="modal-content">'+ data + '</div>' +
          ' </div>' +
          '</div>';

        $(_html)
          .modal()
          .on('hidden.bs.modal', function(){
          $(this).remove();
        });

        setTimeout(function()
        {
          //$('input:visible:first').focus();

          $('#'+id+' form').on('submit',function(e)
          {
            e.preventDefault();

            var postData = $(this).serializeArray();
            var formURL  = $(this).attr("action");

            $.ajax({
              url : formURL,
              type: "POST",
              data : postData,
              complete:function(data, textStatus, jqXHR)
              {
                $('#'+id).modal('hide');

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
        { name: 'document', items: [ ] }, 
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

$('document').ready(function()
{
  updateModalBtn();
});

