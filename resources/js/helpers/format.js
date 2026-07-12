export function formatPrice(value, symbol = '$') {
    const number = Number(value ?? 0);

    return `${number.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ${symbol}`;
}

export function resolveImage(path) {
    if (!path) {
        return 'https://placehold.co/600x600?text=No+Image';
    }

    return path.startsWith('http') ? path : `/storage/${path}`;
}

export function formatDate(value) {
    if (!value) return '';

    return new Date(value).toLocaleDateString('ar-EG', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
}
