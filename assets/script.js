// Remember scroll position per page across reloads. Restored as early as
// possible (script runs after the DOM is parsed) and corrected again once
// images have finished loading and may have shifted the page height.
(() => {
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    const scrollKey = 'scrollY:' + location.pathname;

    const restoreScroll = () => {
        const saved = sessionStorage.getItem(scrollKey);
        if (saved !== null) {
            window.scrollTo(0, parseInt(saved, 10));
        }
    };

    const saveScroll = () => sessionStorage.setItem(scrollKey, window.scrollY);

    restoreScroll();
    window.addEventListener('load', restoreScroll);

    let saveTimeout;
    window.addEventListener('scroll', () => {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveScroll, 100);
    });
    window.addEventListener('beforeunload', saveScroll);
})();

document.addEventListener('DOMContentLoaded', () => {
    const logo = document.querySelector('.logo');
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('#main-nav');
    const overlay = document.querySelector('.nav-overlay');

    if (!toggle || !nav || !overlay) return;

    const closeNav = () => {
        nav.classList.remove('active');
        toggle.classList.remove('active');
        overlay.classList.remove('active');
        toggle.setAttribute('aria-expanded', 'false');
    };

    const toggleNav = () => {
        const isOpen = nav.classList.toggle('active');
        toggle.classList.toggle('active', isOpen);
        overlay.classList.toggle('active', isOpen);
        toggle.setAttribute('aria-expanded', String(isOpen));
    };

    toggle.addEventListener('click', toggleNav);
    overlay.addEventListener('click', closeNav);
    nav.querySelectorAll('a').forEach(link => link.addEventListener('click', closeNav));

    document.querySelectorAll('.carousel').forEach(carousel => {
        const track = carousel.querySelector('.carousel-track');
        const slides = carousel.querySelectorAll('.carousel-slide');
        const prevBtn = carousel.querySelector('.carousel-btn.prev');
        const nextBtn = carousel.querySelector('.carousel-btn.next');
        const dots = carousel.querySelectorAll('.carousel-dot');
        let index = 0;

        const goTo = newIndex => {
            index = (newIndex + slides.length) % slides.length;
            track.style.transform = `translateX(-${index * 100}%)`;
            dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
        };

        prevBtn && prevBtn.addEventListener('click', () => goTo(index - 1));
        nextBtn && nextBtn.addEventListener('click', () => goTo(index + 1));
        dots.forEach((dot, i) => dot.addEventListener('click', () => goTo(i)));
    });

    document.querySelectorAll('.edit-toggle-btn').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.room').classList.add('editing'));
    });

    document.querySelectorAll('.cancel-edit-btn').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.room').classList.remove('editing'));
    });

    document.querySelectorAll('.delete-room-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            if (!confirm('Weet je zeker dat je deze kamer wilt verwijderen? Dit kan niet ongedaan worden gemaakt.')) {
                e.preventDefault();
            }
        });
    });

    // Photo add/delete forms cause a full page reload; remember which room
    // was open so it reopens in edit mode and the change is visible.
    document.querySelectorAll('.add-photo-form, .delete-photo-form').forEach(form => {
        form.addEventListener('submit', () => {
            const room = form.closest('.room');
            if (room) sessionStorage.setItem('reopenEditingRoom', room.id);
        });
    });

    const reopenRoomId = sessionStorage.getItem('reopenEditingRoom');
    if (reopenRoomId) {
        sessionStorage.removeItem('reopenEditingRoom');
        const room = document.getElementById(reopenRoomId);
        if (room) room.classList.add('editing');
    }
});
