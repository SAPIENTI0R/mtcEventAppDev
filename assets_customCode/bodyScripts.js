// Declare all Capacitor Integrations
const FirebaseMessaging = window.Capacitor.Plugins.FirebaseMessaging;
const FirebaseFunctions = window.Capacitor.Plugins.FirebaseFunctions;
const Toast = window.Capacitor.Plugins.Toast;
const Haptics = window.Capacitor.Plugins.Haptics;
const App = window.Capacitor.Plugins.App;
const BarcodeScanner = window.Capacitor.Plugins.CapacitorBarcodeScanner;
const Browser = window.Capacitor.Plugins.Browser;
const Dialog = window.Capacitor.Plugins.Dialog;
const Geolocate = window.Capacitor.Plugins.Geolocation;

// ----- Request notification permissions -----
const handleRequestPermission = async () => {
  try {
    const result = await FirebaseMessaging.requestPermissions();
    const result2 = await FirebaseMessaging.getToken();
  } catch (error) {
    console.error('Error requesting permissions:', error);
  }
};
