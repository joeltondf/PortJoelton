// GSAP Registration
gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    // ================================================================
    // Hero Animations
    // ================================================================
    const heroTl = gsap.timeline();
    
    heroTl.from('.hero-tag', { y: 20, opacity: 0, duration: 1, ease: 'power4.out', delay: 0.5 })
          .from('.hero-title', { y: 30, opacity: 0, duration: 1.5, ease: 'power4.out' }, '-=0.8')
          .from('.hero-p', { y: 20, opacity: 0, duration: 1, ease: 'power3.out' }, '-=1.2')
          .from('.hero-btns', { y: 20, opacity: 0, duration: 1, ease: 'power3.out' }, '-=1.2');

    // Hero Mouse Tilt (Perspective)
    const heroSection = document.getElementById('hero');
    const heroVisuals = document.querySelector('.hero-visuals');
    if (heroSection && heroVisuals) {
        heroSection.addEventListener('mousemove', (e) => {
            const { clientX, clientY } = e;
            const { innerWidth, innerHeight } = window;
            const xPos = (clientX / innerWidth - 0.5) * 8;
            const yPos = (clientY / innerHeight - 0.5) * 8;
            
            gsap.to(heroVisuals, {
                duration: 1.2,
                rotateY: xPos,
                rotateX: -yPos,
                ease: 'power2.out',
                transformPerspective: 1200
            });
        });

        heroSection.addEventListener('mouseleave', () => {
            gsap.to(heroVisuals, {
                duration: 2,
                rotateY: 0,
                rotateX: 0,
                ease: 'elastic.out(1, 0.3)'
            });
        });
    }

    // ================================================================
    // Section Reveals — Headings e cards
    // ================================================================
    const revealItems = document.querySelectorAll('h2, .glass-card:not(.project-item)');
    
    revealItems.forEach(item => {
        gsap.from(item, {
            scrollTrigger: {
                trigger: item,
                start: 'top 90%',
                once: true
            },
            y: 20,
            opacity: 0,
            duration: 0.8,
            ease: 'power2.out',
            onComplete: () => {
                item.style.opacity = "1";
                item.style.visibility = "visible";
            }
        });
    });

    // ================================================================
    // Task 4 — Project Grid Stagger (home)
    // ================================================================
    gsap.from('.project-item', {
        scrollTrigger: {
            trigger: '#projects-grid',
            start: 'top 85%',
            once: true
        },
        y: 50,
        opacity: 0,
        duration: 0.8,
        stagger: 0.15,
        ease: 'power3.out',
        onComplete: function() {
            document.querySelectorAll('.project-item').forEach(item => {
                item.style.opacity = "1";
                item.style.visibility = "visible";
            });
        }
    });

    // ================================================================
    // Task 4 — Gallery Stagger (project.php)
    // Anima as imagens da galeria em cascata com fade-up + scale
    // ================================================================
    const galleryGrid = document.querySelector('.gallery-grid');
    if (galleryGrid) {
        gsap.fromTo('.gallery-item', 
            { 
                y: 50, 
                opacity: 0, 
                scale: 0.95,
            },
            {
                y: 0,
                opacity: 1,
                scale: 1,
                duration: 0.75,
                stagger: {
                    each: 0.13,
                    ease: 'power1.in'
                },
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.gallery-grid',
                    start: 'top 88%',
                    once: true,
                }
            }
        );
    }

    // ================================================================
    // Task 4 — Header do Projeto — fade-up ao entrar
    // ================================================================
    const projectHeader = document.querySelector('.project-header');
    if (projectHeader) {
        gsap.from(projectHeader, {
            y: 40,
            opacity: 0,
            duration: 1.2,
            ease: 'power4.out',
            delay: 0.3
        });
    }

    // ================================================================
    // Task 4 — Parallax Suave nas Imagens de Fundo
    // ================================================================
    document.querySelectorAll('.parallax-bg').forEach(img => {
        const container = img.closest('.parallax-container');
        if (!container) return;
        
        gsap.to(img, {
            yPercent: -15,
            ease: 'none',
            scrollTrigger: {
                trigger: container,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1.5,
            }
        });
    });

    // ================================================================
    // Parallax adicional nos Projetos (Movimento extra na tela)
    // ================================================================
    gsap.utils.toArray('.project-item').forEach(item => {
        const img = item.querySelector('img');
        if (img) {
            gsap.to(img, {
                yPercent: 10,
                scale: 1.1,
                ease: "none",
                scrollTrigger: {
                    trigger: item,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: true
                }
            });
        }
    });

    // ================================================================
    // Interação Película do Mouse (Spotlight) 
    // Segue o mouse dentro dos blocos dos projetos
    // ================================================================
    const spotlightWrappers = document.querySelectorAll('.spotlight-wrapper');
    spotlightWrappers.forEach(wrapper => {
        wrapper.addEventListener('mousemove', (e) => {
            const rect = wrapper.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            wrapper.style.setProperty('--mouse-x', `${x}px`);
            wrapper.style.setProperty('--mouse-y', `${y}px`);
        });
    });

    window.addEventListener('load', () => ScrollTrigger.refresh());

    // ================================================================
    // Chart Animation
    // ================================================================
    gsap.from('.chart-bar', {
        scrollTrigger: {
            trigger: '#skills',
            start: 'top center',
        },
        scaleY: 0,
        transformOrigin: 'bottom',
        duration: 2,
        stagger: 0.1,
        ease: 'elastic.out(1, 0.5)'
    });

    // ================================================================
    // Filter Logic
    // ================================================================
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectItems = document.querySelectorAll('.project-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.getAttribute('data-filter');
            
            filterBtns.forEach(b => b.classList.remove('active', 'border-violet-500/50', 'text-white'));
            btn.classList.add('active', 'border-violet-500/50', 'text-white');

            projectItems.forEach(item => {
                let categories = [];
                try {
                    categories = JSON.parse(item.getAttribute('data-category')) || [];
                } catch(e) {
                    categories = [item.getAttribute('data-category')];
                }

                if (filter === 'all' || categories.includes(filter)) {
                    gsap.to(item, { scale: 1, opacity: 1, duration: 0.4, display: 'block' });
                } else {
                    gsap.to(item, { scale: 0.8, opacity: 0, duration: 0.4, display: 'none' });
                }
            });
        });
    });

    // ================================================================
    // Form Masking & Submission
    // ================================================================
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', (e) => {
            let val = e.target.value.replace(/\D/g, '');
            if (val.length > 11) val = val.substring(0, 11);
            
            let masked = "";
            if (val.length > 0) masked += "(" + val.substring(0, 2);
            if (val.length > 2) masked += ") " + val.substring(2, 7);
            if (val.length > 7) masked += "-" + val.substring(7, 11);
            
            e.target.value = masked;
        });
    }

    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(contactForm);
            const data = Object.fromEntries(formData.entries());
            
            const submitBtn = contactForm.querySelector('button');
            submitBtn.innerText = 'ENVIANDO...';
            submitBtn.disabled = true;

            try {
                const response = await fetch(`${window.BASE_URL}/api/contact.php`, {
                    method: 'POST',
                    body: JSON.stringify(data),
                    headers: { 'Content-Type': 'application/json' }
                });

                if (response.ok) {
                    gsap.to(contactForm, { opacity: 0, duration: 0.5, onComplete: () => {
                        contactForm.classList.add('hidden');
                        const successEl = document.getElementById('form-success');
                        if (successEl) successEl.classList.remove('hidden');
                    }});
                } else {
                    alert('Erro ao enviar.');
                    submitBtn.innerText = 'ENVIAR';
                    submitBtn.disabled = false;
                }
            } catch (err) {
                console.error(err);
                submitBtn.innerText = 'ERRO';
            }
        });
    }
});
