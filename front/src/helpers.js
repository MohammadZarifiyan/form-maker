import { Modal } from "bootstrap";

function hideModal(id) {
    const modal = new Modal(document.getElementById(id));

    modal._hideModal();

    const backdrops = document.getElementsByClassName("modal-backdrop");

    for (let backdrop of backdrops) {
        backdrop.remove();
    }
}

export { hideModal };
