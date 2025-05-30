import './bootstrap';
import * as bootstrap from 'bootstrap';
import jQuery from "jquery";
import 'datatables.net';
import 'datatables.net-bs5';
import Inputmask from 'inputmask';
import Swal from 'sweetalert2';
import select2 from'select2';

window.$ = jQuery;
window.jQuery = jQuery;
window.Swal = Swal;
select2();

import.meta.glob([
    '../images/**',
]);

// Initialize all tooltips
document.addEventListener('DOMContentLoaded', () => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});