/* =========================================
 * custom range slider
 * ========================================= */

(function () {
  function emit(target, name) {
    var event;
    if (document.createEvent) {
      event = document.createEvent("HTMLEvents");
      event.initEvent(name, true, true);
    } else {
      event = document.createEventObject();
      event.eventType = name;
    }

    event.eventName = name;

    if (document.createEvent) {
      target.dispatchEvent(event);
    } else {
      target.fireEvent("on" + event.eventType, event);
    }
  }

  var outputsSelector = "input[type=number][source],select[source]";

  function onChange(e) {
    var outputs = document.querySelectorAll(outputsSelector);
    for (var index = 0; index < outputs.length; index++) {
      var item = outputs[index];
      var source = document.querySelector(item.getAttribute("source"));
      if (source) {
        if (item === e.target) {
          source.value = item.value;
          emit(source, "input");
          emit(source, "change");
        }

        if (source === e.target) {
          item.value = source.value;
        }
      }
    }
  }

  document.addEventListener("change", onChange);
  document.addEventListener("input", onChange);
})();

/* =========================================
 * Selectize
 * ========================================= */
jQuery(function ($) {
  $(".selectize").selectize({
    sortField: "text",
  });

  // Floating label
  //TODO #2
  $(".selectize-input")
  .focusin(function () {
    $(this).parent(".selectize-control").addClass("focus");
  })
  if (!$(this).hasClass(".has-items")) {
    $(this).focusout(function () {
        $(this).parent(".selectize-control").removeClass("focus");
      })
    };
});

/* =========================================
 * Media uploader
 * ========================================= */

//TODO #5 Zprovoznění scryptu pro více formulářových polí
//TODO #6
jQuery(document).ready(function($){
  $('#wds-media-upload').click(function(e) {
      e.preventDefault();
      var image = wp.media({ 
          title: 'Upload Image',
          // mutiple: true if you want to upload multiple files at once
          multiple: false
      }).open()
      .on('select', function(e){
          // This will return the selected image from the Media Uploader, the result is an object
          var uploaded_image = image.state().get('selection').first();
          // We convert uploaded_image to a JSON object to make accessing it easier
          // Output to the console uploaded_image
          console.log(uploaded_image);
          var image_url = uploaded_image.toJSON().url;
          // Let's assign the url value to the input field
          $('#wds-media-url').val(image_url);
      });
  });
});

