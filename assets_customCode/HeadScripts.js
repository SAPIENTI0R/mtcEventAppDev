// ----- Intercept pdf clicks and open them using Capacitor ----- 
document.addEventListener('click', function(e) {
  const element = e.target.closest('a');
  if (element && element.href.endsWith('.pdf')) {
    e.preventDefault();
    
    // If running in Capacitor
    if (window.Capacitor && window.Capacitor.Plugins.Browser) {
      window.Capacitor.Plugins.Browser.open({ url: element.href });
    } else {
      // Fallback for regular web users
      window.open(element.href, '_blank');
    }
  }
});