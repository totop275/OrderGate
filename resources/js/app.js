import './bootstrap';
import * as bootstrap from 'bootstrap';
import jQuery from "jquery";
import 'datatables.net';
import 'datatables.net-bs5';
import Inputmask from 'inputmask';
import Swal from 'sweetalert2';

window.$ = jQuery;
window.jQuery = jQuery;
window.Swal = Swal;

import.meta.glob([
    '../images/**',
]);

// Initialize all tooltips
document.addEventListener('DOMContentLoaded', () => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});