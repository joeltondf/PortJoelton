// GSAP Registration
gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    // ================================================================
    // Custom Premium Cursor
    // ================================================================
    const cursor = document.querySelector('.custom-cursor');
    const cursorDot = document.querySelector('.custom-cursor-dot');
    
    if (cursor && cursorDot) {
        // Use GSAP quickTo for premium elastic/smooth mouse tracking
        const cursorX = gsap.quickTo(cursor, "left", { duration: 0.4, ease: "power3.out" });
        const cursorY = gsap.quickTo(cursor, "top", { duration: 0.4, ease: "power3.out" });
        
        const dotX = gsap.quickTo(cursorDot, "left", { duration: 0.1, ease: "power2.out" });
        const dotY = gsap.quickTo(cursorDot, "top", { duration: 0.1, ease: "power2.out" });
        
        window.addEventListener('mousemove', (e) => {
            cursorX(e.clientX);
            cursorY(e.clientY);
            dotX(e.clientX);
            dotY(e.clientY);
        });

        // Hide/Show cursor when leaving/entering window
        document.addEventListener('mouseleave', () => {
            gsap.to([cursor, cursorDot], { opacity: 0, duration: 0.3 });
        });
        document.addEventListener('mouseenter', () => {
            gsap.to([cursor, cursorDot], { opacity: 1, duration: 0.3 });
        });

        // Project Items Hover Interactivity (Morphing Cursor with Negative Circle)
        const projectItems = document.querySelectorAll('.project-item, .gallery-item, .glightbox-trigger');
        projectItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                cursor.classList.add('gallery-hover-active');
                gsap.to(cursorDot, { scale: 0, opacity: 0, duration: 0.2 });
            });
            item.addEventListener('mouseleave', () => {
                cursor.classList.remove('gallery-hover-active');
                gsap.to(cursorDot, { scale: 1, opacity: 1, duration: 0.2 });
            });
        });
    }

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
    // Dynamic Reveal Masking on Headings
    // ================================================================
    const headings = document.querySelectorAll('h2');
    headings.forEach(heading => {
        heading.classList.add('mask-reveal');
        gsap.to(heading, {
            scrollTrigger: {
                trigger: heading,
                start: 'top 85%',
                once: true
            },
            clipPath: 'inset(0 0% 0 0)',
            duration: 1.6,
            ease: 'power3.out'
        });
    });

    // ================================================================
    // Scroll-Triggered Parallax (Quem Sou)
    // ================================================================
    // Horizontal text sliding
    const parallaxTexts = document.querySelectorAll('.scroll-parallax-x');
    parallaxTexts.forEach(txt => {
        const speed = parseFloat(txt.getAttribute('data-speed')) || 10;
        gsap.to(txt, {
            xPercent: speed,
            ease: 'none',
            scrollTrigger: {
                trigger: txt,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    });

    // Vertical image scroll parallax
    const aboutImg = document.querySelector('.about-parallax-img');
    if (aboutImg) {
        gsap.to(aboutImg, {
            yPercent: -20,
            ease: 'none',
            scrollTrigger: {
                trigger: '.parallax-container',
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    }

    // ================================================================
    // Section Reveals — glass cards
    // ================================================================
    const revealCards = document.querySelectorAll('.glass-card:not(.project-item)');
    revealCards.forEach(item => {
        gsap.from(item, {
            scrollTrigger: {
                trigger: item,
                start: 'top 90%',
                once: true
            },
            y: 35,
            opacity: 0,
            duration: 1.2,
            ease: 'power3.out'
        });
    });

    // ================================================================
    // Project Grid Stagger
    // ================================================================
    gsap.from('.project-item', {
        scrollTrigger: {
            trigger: '#projects-grid',
            start: 'top 85%',
            once: true
        },
        y: 60,
        opacity: 0,
        duration: 1.2,
        stagger: 0.2,
        ease: 'power3.out'
    });

    // ================================================================
    // Gallery Stagger (project.php)
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
                duration: 0.9,
                stagger: {
                    each: 0.15,
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
    // Project Header (project.php)
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
    // Parallax on Background Images
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
    // Scrollytelling Skill Progress Bars (Expertise)
    // ================================================================
    const progressBars = document.querySelectorAll('.skill-progress-bar');
    progressBars.forEach(bar => {
        const level = bar.getAttribute('data-level') || '0';
        gsap.to(bar, {
            width: `${level}%`,
            duration: 1.8,
            ease: 'power4.out',
            scrollTrigger: {
                trigger: bar,
                start: 'top 95%',
                once: true
            }
        });
    });

    // ================================================================
    // Spotlight & Magnetic Case Button (Magnetism with custom cursor compatibility)
    // ================================================================
    const spotlightWrappers = document.querySelectorAll('.spotlight-wrapper');
    const btnStrength = 0.07;
    const labelStrength = 0.09;
    
    spotlightWrappers.forEach(wrapper => {
        const btn = wrapper.querySelector('.ver-case-btn');
        const label = wrapper.querySelector('.ver-case-label');
        
        wrapper.addEventListener('mousemove', (e) => {
            const rect = wrapper.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            wrapper.style.setProperty('--mouse-x', `${x}px`);
            wrapper.style.setProperty('--mouse-y', `${y}px`);
            
            if (btn) {
                const mapX = gsap.utils.mapRange(rect.left, rect.right, -rect.width / 2, rect.width / 2, e.clientX);
                const mapY = gsap.utils.mapRange(rect.top, rect.bottom, -rect.height / 2, rect.height / 2, e.clientY);
                
                gsap.to(btn, {
                    x: mapX * btnStrength,
                    y: mapY * btnStrength,
                    duration: 0.4,
                    ease: "power2.out",
                    overwrite: true
                });
                
                if (label) {
                    gsap.to(label, {
                        x: mapX * labelStrength,
                        y: mapY * labelStrength,
                        duration: 0.4,
                        ease: "power2.out",
                        overwrite: true
                    });
                }
            }
        });
        
        wrapper.addEventListener('mouseleave', () => {
            if (btn) {
                gsap.to(btn, {
                    x: 0,
                    y: 0,
                    duration: 0.7,
                    ease: "elastic.out(1, 0.4)",
                    overwrite: true
                });
            }
            if (label) {
                gsap.to(label, {
                    x: 0,
                    y: 0,
                    duration: 0.7,
                    ease: "elastic.out(1, 0.4)",
                    overwrite: true
                });
            }
        });
    });

    // Refresh ScrollTrigger calculations
    window.addEventListener('load', () => ScrollTrigger.refresh());

    // ================================================================
    // Filter Cases Logic
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
    // Interactive Automation Terminal Logic
    // ================================================================
    const runBtn = document.getElementById('run-automation-btn');
    const terminalOutput = document.getElementById('terminal-output');
    const statusIndicator = document.getElementById('terminal-status');

    if (runBtn && terminalOutput) {
        const logs = [
            "Initializing automation script: n8n_pipeline_trigger.sh...",
            "Connecting to CRM webhook endpoint (https://api.rdstation.com/v2)... SUCCESS.",
            "Fetching raw customer acquisition parameters...",
            "Syncing dynamic marketing assets with active email campaigns...",
            "Analyzing Conversion Rate Optimization logs (SEO/SEM)...",
            "Starting server-side PHP layout rendering engine...",
            "Compiling WebP optimization workflows... OK.",
            "Pipeline successfully executed. 🌟 Tasks completed: 100%"
        ];

        runBtn.addEventListener('click', () => {
            runBtn.disabled = true;
            runBtn.classList.add('opacity-50', 'cursor-not-allowed');
            statusIndicator.classList.remove('hidden');
            terminalOutput.innerHTML = "";
            
            let delay = 0;
            logs.forEach((log, index) => {
                gsap.delayedCall(delay, () => {
                    const line = document.createElement('div');
                    line.className = "font-mono transition-opacity duration-300 opacity-0";
                    line.innerHTML = `<span class="text-blue-400">&gt;</span> ${log}`;
                    
                    if (index === logs.length - 1) {
                        line.classList.add('text-green-400', 'font-bold');
                    }
                    
                    terminalOutput.appendChild(line);
                    gsap.to(line, { opacity: 1, y: 0, duration: 0.4 });
                    
                    // Auto-scroll terminal output
                    const widget = document.getElementById('terminal-widget');
                    widget.scrollTop = widget.scrollHeight;

                    // Final log completes
                    if (index === logs.length - 1) {
                        statusIndicator.classList.add('hidden');
                        runBtn.disabled = false;
                        runBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        runBtn.innerText = "Executar Novamente";
                    }
                });
                delay += 0.6 + Math.random() * 0.5; // Premium dynamic latency logging simulation
            });
        });
    }

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
