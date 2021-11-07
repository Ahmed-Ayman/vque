/**
 * Place your custom script modifications here.
 */

(function ($, Drupal, window, document) {
  $(document).ready(function () {
    // Add code here, like
    // alert("Welcome to the web site!");
    let counter = document.querySelector(".field.field--name-field-counter.field--type-string.field--label-above .field__item");
    let cValue = counter.innerHTML;

    counter.innerHTML = "";
    $(".field__item").sevenSeg({ value: cValue });

  });
})(jQuery, Drupal, this, this.document);
