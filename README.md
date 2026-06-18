# Middle Tennessee Council Event App <!-- omit from toc -->

**Last updated June 18, 2026**

> The current Android build is available at [/builds/app-debug.apk](/builds/app-debug.apk)

### Table of Contents
- [Core Information](#core-information)
  - [High Level Overview](#high-level-overview)
  - [Updating Content](#updating-content)
  - [Creating a New Event](#creating-a-new-event)
  - [Sending Notifications](#sending-notifications)
  - [Working with the Game](#working-with-the-game)
- [Detailed Documentation](#detailed-documentation)
  - [Software Setup](#software-setup)
    - [Visual Studio Code (VS Code)](#visual-studio-code-vs-code)
    - [NPM, Xcode, Android Studio, and Capacitor](#npm-xcode-android-studio-and-capacitor)
    - [Command Line Notes](#command-line-notes)
  - [Using Capacitor](#using-capacitor)
    - [Plugins](#plugins)
    - [Updating Plugins](#updating-plugins)
    - [App Icons](#app-icons)
    - [App Details](#app-details)
    - [Testing](#testing)
    - [Building and Releasing](#building-and-releasing)
  - [Using Firebase](#using-firebase)
    - [Messaging](#messaging)
    - [Firestore](#firestore)
    - [Functions](#functions)
    - [Hosting](#hosting)

# Core Information

## High Level Overview
This app is built using the [Capacitor](https://capacitorjs.com/) platform. This platform is used to create native Android and iOS apps from a website template. It links to https://app.techmv.com using the `server` command in *`capacitor.config.json`*. This enables all the native app integrations like haptics, notifications, and camera access. It also manages the app icons.

The content of the app is managed using the [WordPress](https://wordpress.org/) platform, which powers over 43% of the internet.

Capacitor injects a JavaScript Bridge into each webpage, accessible at `window.Capacitor`, enabling the website to access the native app functions.

The app uses Google's [Firebase](https://firebase.google.com/) for notifications *(Messaging, Analytics)* and the interactive game *(Firestore Database, Functions)*.

## Updating Content
Content is managed via the WordPress backend. This setup uses the [Elementor](https://elementor.com) website builder within WordPress to design the pages to look like apps. This also provides an intuitive drag-and-drop visual interface to design the webpages.

After each set of changes, press `Publish` in the top right corner. Once it saves, you should use the `Delete Cache` button in the top bar of the main admin page to ensure that all users will load the newest updates.

## Creating a New Event

## Sending Notifications
Notifications are handled through the [Firebase Console](https://console.firebase.google.com/). After logging in and selecting a project, click on `Messaging` on the left (under the `Run` subheading). Click `New Campaign` --> `Notifications`, then enter in the notification information. Under Target, select `Topic` and then select the event (`Message topic`) from the drop down. Click `Next`, and select when you want to send the message.

Press `Review` and then `Publish` to send/schedule the notification.

## Working with the Game

# Detailed Documentation

## Software Setup
All the software used here is available for free, and much of it is open source. XCode can only be run on MacOS and is required for creating iOS (Apple App Store) apps. Everything else can be used on both Windows and MacOS devices.

### Visual Studio Code (VS Code)
This is published by Microsoft and available [here](https://code.visualstudio.com/download). Once installed, click Extensions on the left side panel and search for WebNative and install it. This plugin offers a GUI to modify the Capacitor project easier without the use of a command line.  
To open a folder in VS Code, use `File` --> `Open Folder` or drag the folder onto VS Code. You can save this workspace by using `File` --> `Save Workspace As`. This will make the workspace a file that you can easily open as well as save the settings in the workspace.

### NPM, Xcode, Android Studio, and Capacitor
Go [here](https://nodejs.org/en/download#:~:text=Or%20get%20a%20prebuilt%20Node%2Ejs) to install the Node Package Manager (NPM). It is easiest to install the prebuilt Node.js version by using the section at the bottom.

Follow [these instructions](https://capacitorjs.com/docs/getting-started/environment-setup) to install Xcode, Xcode Command Line Tools, Android Studio, and the Android SDK.

[This page](https://capacitorjs.com/docs/getting-started) shows how to setup Capacitor from scratch if you are building a new app. This shouldn't be needed if you are copying an app. To set up Capacitor in an app that you already copied into a new folder, navigate to the folder in a command line and use `npm install` to install the dependencies.

### Command Line Notes
Many steps here will require the use of a command line. This can be done on MacOS using the `Terminal` app and on Windows using the `Command Prompt` app. You can also use `Terminal` --> `New Terminal` in VS Code to enter the command line interface. This will open a command line interface inside the current folder. The table below gives the navigation commands used by the command line interface.

| Action                     | Windows          | macOS            |
| :------------------------- | :--------------- | :--------------- |
| **List files and folders** | `dir`            | `ls`             |
| **Enter a folder**         | `cd folder_name` | `cd folder_name` |
| **Go up one level**        | `cd ..`          | `cd ..`          |
| **Clear the terminal**     | `cls`            | `clear`          |

## Using Capacitor

### Plugins
These are the Capacitor plugins that are used by the app. Most of them are official first-party plugins, but the Firebase ones are from [this](https://github.com/capawesome-team/capacitor-firebase/tree/main) Github repo.

The documentation for the first-party plugins is available [here](https://capacitorjs.com/docs/apis).

| Plugin                             | Use                                                       | Javascript Access (window.Capacitor.Plugins) |
| :--------------------------------- | :-------------------------------------------------------- | :------------------------------------------- |
| **@capacitor-firebase/analytics**  | Resetting notification topic subscriptions                | `.FirebaseAnalytics`                         |
| **@capacitor-firebase/functions**  | Calling the Firebase Functions that operate the game      | `.FirebaseFunctions`                         |
| **@capacitor-firebase/messaging**  | Push notifications and topic subscriptions                | `.FirebaseMessaging`                         |
| **@capacitor/app**                 | Detecting changes in app state                            | `.App`                                       |
| **@capacitor/barcode-scanner**     | Scanning barcodes for the game                            | `.CapacitorBarcodeScanner`                   |
| **@capacitor/browser**             | Opening links, particularly to files                      | `.Browser`                                   |
| **@capacitor/dialog**              | Sending dialog popup messages, mostly for debugging       | `.Dialog`                                    |
| **@capacitor/geolocation**         | Unused                                                    | `.Geolocation`                               |
| **@capacitor/haptics**             | Haptic feedback, used after a successful barcode scan     | `.Haptics`                                   |
| **@capacitor/local-notifications** | Unused                                                    | `.`                                          |
| **@capacitor/splash-screen**       | Provides the splash screen at startup                     | Not used in JavaScript                       |
| **@capacitor/toast**               | Sends toast messages. Used in the game and for debugging` | `.Toast`                                     |

### Updating Plugins
Plugins can be updated using the command line interface, but the easiest way is to use the WebNative VS Code Extension.

Click on the extension in the left panel (WN) and click on `Packages`. Available updates are shown `NAME #.#.# --> #.#.#`. To update all the packages at once, hover where it says `@capacitor` and click the lightbulb icon. Then click `Upgrade` in the window that pops up.

The same process can be used for the plugins (`@capacitor-firebase` and `@capacitor`) which are below.

You will then need to press `Sync` (under `Projects`) to sync the updates to their respective platform folders. Then follow the [Releasing](#building-and-releasing) directions below.

### App Icons

### App Details

### Testing

### Building and Releasing

## Using Firebase
`cd firebase`

`firebase login --reauth`

`firebase emulators:start`

### Messaging

### Firestore

### Functions
`firebase deploy --only functions`

### Hosting
`firebase deploy --only hosting`