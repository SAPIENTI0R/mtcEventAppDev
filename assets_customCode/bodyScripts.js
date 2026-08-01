const FirebaseMessaging = window.Capacitor.Plugins.FirebaseMessaging;
const Toast = window.Capacitor.Plugins.Toast;
// ----- Request notification permissions -----
const handleRequestPermission = async () => {
  try {
    const result = await FirebaseMessaging.requestPermissions();
    const result2 = await FirebaseMessaging.getToken();
  } catch (error) {
    console.error('Error requesting permissions:', error);
  }
};
