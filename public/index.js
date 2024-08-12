document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('a[href^="#"]');
    const sections = document.querySelectorAll('#overview, #user-management, #role-management, #permission-management, #reports, #notifications');

    links.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();

            const targetId = link.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);

            sections.forEach(section => {
                section.classList.add('hidden');
            });

            targetSection.classList.remove('hidden');
        });
    });

    const modal = document.getElementById('modal');
    const openModalButton = document.getElementById('openModalButton');
    const closeModalButton = document.getElementById('closeModalButton');

    openModalButton.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('show');
    });

    closeModalButton.addEventListener('click', () => {
        modal.classList.remove('show');
        modal.classList.add('hidden');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
            modal.classList.add('hidden');
        }
    });
});


