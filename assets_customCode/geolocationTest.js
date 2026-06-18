window.onload = async function () {
    const isApp = !!(window.Capacitor);

    if (isApp) {
        const Geolocation = window.Capacitor.Plugins.Geolocation;
        const Toast = window.Capacitor.Plugins.Toast;

        const printCurrentPosition = async () => {
            const coordinates = await Geolocation.getCurrentPosition();
            console.log('Current position:', coordinates.coords);
            return coordinates.coords;
        };
    }
};