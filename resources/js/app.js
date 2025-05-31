import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import * as bootstrap from 'bootstrap';
import jQuery from "jquery";
import 'datatables.net';
import 'datatables.net-bs5';
import Inputmask from 'inputmask';
import Swal from 'sweetalert2';
import select2 from'select2';
import toastr from 'toastr';

jQuery.ajaxSetup({
    headers: {
        'Accept': 'application/json',
    }
});

window.$ = jQuery;
window.jQuery = jQuery;
window.Swal = Swal;
window.bootstrap = bootstrap;
window.toastr = toastr;
select2();

import.meta.glob([
    '../images/**',
]);

document.addEventListener('DOMContentLoaded', () => {
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    
    // Initialize all modals
    const modalTriggerList = document.querySelectorAll('[data-bs-toggle="modal"]');
    const modalList = [...modalTriggerList].map(modalTriggerEl => new bootstrap.Modal(modalTriggerEl));
});