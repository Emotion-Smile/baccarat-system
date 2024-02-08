import Swal from 'sweetalert2';

export async function createNewMatchConfirmDialog(text = 'Do you want to start new match?', title = '') {
    return Swal.fire({
        title: title,
        input: 'number',
        text: text,
        inputAttributes: {
            placeHolder: 'Set fight number or leave it blank'
        },
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'YES',
        cancelButtonText: 'NO',
    });
}

export async function confirmDialog(text, title = '') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'YES',
        cancelButtonText: 'NO'
    });
}

export function alertInfo(text, title = '') {
    Swal.fire({
        title: title,
        text: text,
        icon: 'info',
    });
}

export function getColorByStatus(status) {
    let color = 'bg-green-500';

    if (status.toLowerCase() === 'live'){
        color = 'bg-yellow-500';
    }

    if (status.toLowerCase() === 'close') {
        color = 'bg-gray-500';
    }

    return color;
}

