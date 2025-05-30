import './bootstrap';
import * as bootstrap from 'bootstrap';

import.meta.glob([
    '../images/**',
]);

// Initialize all tooltips
document.addEventListener('DOMContentLoaded', () => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});