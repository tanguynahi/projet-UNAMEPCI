import './bootstrap';

require('datatables.net-bs4');
require('datatables.net-buttons-bs4');
require('datatables.net-buttons/js/buttons.html5.js');
require('datatables.net-buttons/js/buttons.print.js');


// resources/js/app.js

// Importer jQuery
const $ = require('jquery');
window.$ = $;

// Importer DataTables
require('datatables.net-bs4')();
require('datatables.net-buttons-bs4')();

// Importer les plugins de boutons
require('datatables.net-buttons/js/buttons.html5.js')();
require('datatables.net-buttons/js/buttons.print.js')();

// Importer les bibliothèques nécessaires pour l'exportation
require('jszip');
require('pdfmake');

import Swal from 'sweetalert2';
