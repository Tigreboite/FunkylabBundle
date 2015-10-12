$.fn.modal.Constructor.prototype.enforceFocus = function() {
  modal_this = this;
  $(document).on('focusin.modal', function (e) {
    if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length
      && !$(e.target.parentNode).hasClass('f-popup-line')
    ){
      modal_this.$element.focus();
    }
  });
};