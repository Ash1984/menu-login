Menu Login
Menu Login is a lightweight WordPress plugin that provides an Elementor widget for an AJAX-based login/register popup. When users are not logged in, clicking the widget icon opens a stylish popup. In login mode, users enter their username and password. In register mode, users enter only their email address; the plugin auto‑generates a username and password, creates the account, and logs the user in. The widget is fully responsive and customizable with extensive style controls.

Features
AJAX Login/Register Popup: Provides an on‑demand popup for user authentication.
Elementor Widget: Seamlessly integrates with Elementor. Drag and drop the “Menu Login Icon” widget into any section.
Auto‑Generated Credentials: On registration, a username (from the email local‑part) and a random password are created automatically.
Responsive & Customizable: Configure all styling—including hover, active, and focus states—from the Elementor editor.
Loading Spinner Animation: Displays an animated loading spinner during the login process.
WooCommerce Integration: Redirects logged‑in users to the My Account page if WooCommerce is active.
Editor Demo Mode: Includes a “Pre-show Popup Demo” button for previewing the popup in the Elementor editor.
Installation
Download or clone this repository.
Place the entire menu-login folder in your /wp-content/plugins/ directory.
In your WordPress admin dashboard, navigate to Plugins and activate Menu Login.
Make sure you have Elementor active (and optionally WooCommerce if needed).
Usage
Open your page with Elementor.
In the Elementor widget panel, search for Menu Login Icon.
Drag and drop the widget onto your page.
Configure content (icon, welcome message, login title) and style settings using Elementor’s panel.
On the front end, if a non‑logged‑in user clicks the widget icon (with AJAX enabled), the login/register popup appears.
Logged‑in users see their avatar (or initial) along with a welcome message.
Folder Structure
python
Copy
menu-login
├── assets
│   ├── css
│   │   └── menu-login.css       # Static CSS and keyframes for spinner animation.
│   └── js
│       └── menu-login-editorv1.js  # Editor-specific JavaScript.
├── includes
│   ├── class-menu-login-ajax.php   # AJAX handlers.
│   └── class-menu-login-widget.php # Elementor widget and dynamic CSS.
└── menu-login.php              # Main plugin file.
Contributing
Contributions are welcome!
Feel free to fork the repository, make changes, and open a pull request.
Please follow standard GitHub practices for issues and pull requests.

License
This project is licensed under the GPL2 License.

