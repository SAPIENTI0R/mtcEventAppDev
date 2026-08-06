<?php
function add_capacitor_pull_to_refresh()
{
  // Load PullToRefresh library from CDN
  wp_enqueue_script(
    'pulltorefresh',
    'https://cdnjs.cloudflare.com/ajax/libs/pulltorefreshjs/0.1.22/index.umd.min.js',
    array(),
    null,
    true
  );

  // Initialize the script
  wp_add_inline_script('pulltorefresh', "
document.addEventListener('DOMContentLoaded', function() {
  PullToRefresh.init({
    mainElement: 'body',
    shouldPullToRefresh: function() {
      // 1. Check if the Leaflet map element exists and has the fullscreen class
      var mapEl = document.getElementById('bsr-map');
      var isMapFullscreen = mapEl && mapEl.classList.contains('leaflet-fullscreen-on');
      
      // 2. Default pull-to-refresh behavior requires the page to be scrolled to the top.
      // We also require that the map is NOT in fullscreen.
      return !isMapFullscreen && window.scrollY === 0;
    },
    onRefresh: function() {
      window.location.reload();
    }
  });

  var btn = document.getElementById('back-button');
  if (!btn) return;

  // The library moves the page by setting min-height on a .ptr--ptr element
  // it creates lazily on the first pull. Watch that element's min-height and
  // mirror it onto the back button so it pulls down with the page.
  //
  // While the user is actively pulling, the element has the .ptr--pull class
  // (transition: none), so the button follows the finger instantly. When the
  // pull is released, the library sets the final min-height (distReload while
  // refreshing, or 0 otherwise) BEFORE removing .ptr--pull, so the page snaps
  // to that value instantly. We must do the same: track the pulling state
  // from the class mutations (which are recorded after the style mutation in
  // the same batch) so the button snaps in sync with the page instead of
  // animating behind it. After the pull ends, the library re-enables the
  // page's 0.3s min-height transition, so we mirror that with a matching
  // 0.3s transform transition on the button.
  var pulling = false;
  var styleObserver = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
        pulling = mutation.target.classList.contains('ptr--pull');
        btn.style.transition = pulling ? 'none' : 'transform 0.3s ease';
      } else if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
        var dist = parseFloat(mutation.target.style.minHeight) || 0;
        btn.style.transform = 'translateY(' + dist + 'px)';
      }
    });
  });

  // The .ptr--ptr element is inserted into the body on the first pull, so
  // watch the body for it to appear, then start observing its style. After a
  // refresh completes, the library removes that element and creates a brand
  // new one on the next pull, so we must keep watching the body and re-attach
  // the style observer to each new element. When the element is removed, we
  // reset the button back to its resting position.
  var bodyObserver = new MutationObserver(function() {
    var ptr = document.querySelector('.ptr--ptr');
    if (ptr && !ptr._ptrObserved) {
      ptr._ptrObserved = true;
      styleObserver.observe(ptr, { attributes: true, attributeFilter: ['style', 'class'] });
    } else if (!ptr) {
      btn.style.transition = 'transform 0.3s ease';
      btn.style.transform = 'translateY(0)';
      setTimeout(function() {
        btn.style.transition = '';
      }, 300);
    }
  });
  bodyObserver.observe(document.body, { childList: true, subtree: true });
});
");
}
add_action('wp_enqueue_scripts', 'add_capacitor_pull_to_refresh');