// any CSS you import will output into a single css file (app.css in this case)
import '../styles/app.scss';
import 'admin-lte/dist/css/adminlte.min.css';

// FontAwesome
import '@fortawesome/fontawesome-free/css/all.css';

// Scripts AdminLTE
import 'admin-lte/dist/js/adminlte.min.js';

// jQuery & Bootstrap
const $ = require('jquery');
require('bootstrap');

// Tes fichiers custom
require('./btnDelete.js');
require('./updateStatus.js');