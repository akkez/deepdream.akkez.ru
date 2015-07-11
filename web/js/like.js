$(function() {
  var st = function () {
    var storage = false;
    try {
      storage = 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
      return false;
    }
    if (!storage) {
      return false;
    }
    return window['localStorage'];
  };

  $(".like-btn").click(function () {
    if (!$(this).hasClass('like-btn')) {
      return;
    }
    $(this).removeClass('like-btn').removeClass('btn-primary').addClass('btn-default');
    $(this).find('.like-text').remove();

    var btn = $(this);
    var id = $(this).data('picture-id');
    var h = $(this).data('ignore-this-hahaha'); //i lied
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
      method:   "POST",
      dataType: 'text',
      url:      "/picture/like",
      data:     {
        'p':     id,
        'h':     h,
        '_csrf': csrfToken
      },
      headers:  {
        'X-Like': 'True'
      },
      success:  function (data) {
        if (data.length > 1 && data[0] == '+') {
          var count = parseInt(data);
          btn.find(".count").text(count);
        }
        var s = st();
        if (s) {
          s.setItem(id, "1");
        }
      }
    });
  });

  $(function () {
    var s = st();
    if (!s) {
      return;
    }
    $(".like-btn").each(function () {
      var r = s.getItem($(this).data("picture-id"));
      if (r != null) {
        $(this).removeClass('like-btn').removeClass('btn-primary').addClass('btn-default');
        $(this).find('.like-text').remove();
      }
    });
  });
});
