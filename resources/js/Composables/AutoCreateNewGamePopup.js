import Swal from 'sweetalert2';

export const useAutoCreateNewGamePopup = function (callback = () => {}, timerInSeconds) {
    let timerInterval;

    Swal.fire({
        title: 'Create New Game',
        html: 'Auto create new game in <strong></strong> seconds.',
        timer: timerInSeconds * 1000,
        showCancelButton: false,
        showConfirmButton: false,
        allowOutsideClick: false,
        willOpen: () => {
            Swal.showLoading();

            timerInterval = setInterval(() => {
                Swal.getHtmlContainer().querySelector('strong').textContent = timerInSeconds--;
                console.log('Countdown...');
            }, 1000);
        }
    })
        .then((result) => {
            if(result.dismiss === Swal.DismissReason.timer) {
                clearInterval(timerInterval);
                callback();
            }
        });
};