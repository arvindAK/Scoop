$(document).ready(function() {
  $(".result").on("click", function() {
    var url = $(this).attr("href");
    var linkId = $(this).attr("data-linkId");

    increaseLinkClicks(linkId, url);
    return false;
  });

  $("[data-fancybox]").fancybox({
    caption: function(instance, item) {
      var caption = $(this).data("text") || "";
      var siteUrl = $(this).data("siteurl") || "";
      if (item.type === "image") {
        caption =
          (caption.length ? "<h3>" + caption + "</h3>" + "<br />" : "") +
          '<a href="' +
          item.src +
          '">View Image</a> | ' +
          '<a href="' +
          siteUrl +
          '">Visit Page</a>';
      }

      return caption;
    }
  });

  $(".gridItem a").on("click", function() {
    var imageUrl = $(this).attr("href");
    increaseImageClicks(imageUrl);
  });

  window.addEventListener(
    "scroll",
    function() {
      var top = this.pageYOffset + window.innerHeight;
      var images = document.querySelectorAll(".gridItem");
      images.forEach(function(div) {
        if (parseInt($(div).attr("data-ypos")) < top) {
          $(div).addClass("active");
        }
      });
    },
    { passive: true }
  );
});

function increaseLinkClicks(linkId, url) {
  $.post("ajax/updateLinkCount.php", { linkId: linkId }).done(function(result) {
    if (result != "") {
      alert(result);
      return;
    }

    window.location.href = url;
  });
}

function addyPositionValues(className) {
  var rect = $("." + className).position();
  $("." + className).attr("data-ypos", String(rect.top));
  if (window.innerHeight >= rect.top) {
    $("." + className).addClass("active");
  }
}

function increaseImageClicks(imageUrl) {
  $.post("ajax/updateImageCount.php", { imageUrl: imageUrl }).done(function(
    result
  ) {
    if (result != "") {
      alert(result);
      return;
    }

    window.location.href = imageUrl;
  });
}

function loadImage(src, className) {
  var image = $("<img>");
  image.on("load", function() {
    $("." + className + " a").append(image);
    addyPositionValues(className);
  });

  image.on("error", function() {
    $("." + className).remove();
    $.post("ajax/setBrokenLink.php", { src: src });
  });

  image.attr("src", src);
}
