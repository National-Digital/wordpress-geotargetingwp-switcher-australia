jQuery(function (jQuery) {
  jQuery(document).ready(function () {
    if (geot && (/iP(od|hone)/i.test(window.navigator.userAgent) || /IEMobile/i.test(window.navigator.userAgent) || /Windows Phone/i.test(window.navigator.userAgent) || /BlackBerry/i.test(window.navigator.userAgent) || /BB10/i.test(window.navigator.userAgent) || /Android.*Mobile/i.test(window.navigator.userAgent))) {
      geot.dropdown_search = true;
    }
    const switcher_options = {
      onChange: function (state_name) {

        if (!state_name.length)
          return;

        GeotCreateCookie('geot_switcher', state_name, 999);
        window.location.reload();

      }
    };

    if (GeotReadCookie('geot_switcher')) {
      jQuery('.state_switcher').removeClass('switcher_hidden' );
    }


    if(jQuery('.selectize-input.has-options')) {
      jQuery('.selectize-input.has-options').css({
        opacity: 0;
      })
      
      jQuery('.selectize-input').trigger('click');
      jQuery('#aust_state_switcher a').each(function(index, item) {
        jQuery(item).on('click', (e) => {
          jQuery(`.selectize-dropdown-content .option[data-value="${e.target.dataset.state}"]`).trigger( "click" )
        })
      });
    }

    if (jQuery('.geot_switcher').length) {
      const jQuerygeot_switcher = jQuery('.geot_switcher').selectize(switcher_options);
      if (GeotReadCookie('geot_switcher')) {
        const selectize = jQuerygeot_switcher[0].selectize;
        selectize.addItem(GeotReadCookie('geot_switcher'), true);
      }

      jQuery('.selectize-input').trigger('click');
      jQuery('#aust_state_switcher a').each(function(index, item) {
        jQuery(item).on('click', (e) => {
          jQuery(`.selectize-dropdown-content .option[data-value="${e.target.dataset.state}"]`).trigger( "click" )
        })
      });

    }



         

    });


    function GeotCreateCookie(name, value, days) {
      if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
      } else var expires = "";
      document.cookie = name + "=" + value + expires + "; path=/";
    }

    function GeotReadCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
    }
  });