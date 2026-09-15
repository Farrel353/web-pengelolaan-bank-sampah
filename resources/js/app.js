import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Helper global formatting
window.formatRupiah = function(number) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
};

window.formatNumber = function(number) {
    return new Intl.NumberFormat('id-ID').format(number);
};

// Start Alpine
Alpine.start();
