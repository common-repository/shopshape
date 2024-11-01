function shopshapeAddonsCalculate($) {
  let addonPrice = 0;

  // Calculate total addon price
  $(".shopshape-addon-option-input:checked").each(function () {
    addonPrice += parseFloat($(this).val());
  });

  // Display the total price if it's not NaN
  if (!isNaN(addonPrice)) {
    $("#shopshape-addons-total-price").html(
      shopshape.price_html.replace("0.00", addonPrice.toFixed(2))
    );
    $("#shopshape-addons-total").show();
  }
}

jQuery(function ($) {
  // Calculate on option row click
  const $body = $("body");
  $body.on("click", ".shopshape-addon-option-row", function () {
    $(this).find(".shopshape-addon-option-input").prop("checked", true);
    shopshapeAddonsCalculate($);
  });

  // Calculate on option input change
  $body.on("change", ".shopshape-addon-option-input", function () {
    shopshapeAddonsCalculate($);
  });

  // Clear selections and hide total
  $("#shopshape-addons-clear").on("click", function (e) {
    e.preventDefault();
    $(".shopshape-addon-option-input").prop("checked", false);
    $("#shopshape-addons-total").hide();
  });
});
