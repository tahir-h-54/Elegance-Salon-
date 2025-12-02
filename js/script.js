document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    document.documentElement.style.overflow = "auto";
    document.documentElement.classList.add("show-scrollbar");
  }, 5000);
});

const loader = document.querySelector('.loader img');
loader.addEventListener('animationend', () => {
  loader.style.display = 'none';
});


const menu = document.getElementById("menu");
const menuBtn = document.getElementById("menuBtn");
const menuBtnMobile = document.getElementById("menuBtnMobile"); // new mobile button
const closeBtn = document.getElementById("closeBtn");

function openMenu() {
  gsap.to(menu, {
    y: "0%",
    duration: 0.8,
    ease: "power4.out"
  });
}

function closeMenu() {
  gsap.to(menu, {
    y: "-150%",
    duration: 0.8,
    ease: "power4.in"
  });
}

// desktop button
menuBtn.addEventListener("click", openMenu);

// mobile button
if (menuBtnMobile) {
  menuBtnMobile.addEventListener("click", openMenu);
}

// close button
closeBtn.addEventListener("click", closeMenu);


document.addEventListener("DOMContentLoaded", () => {
  gsap.registerPlugin(ScrollTrigger);

  const lenis = new Lenis();
  lenis.on("scroll", ScrollTrigger.update);

  gsap.ticker.add((time) => {
    lenis.raf(time * 1000);
  });

  gsap.ticker.lagSmoothing(0);

  const heroImgFinalPos = [
    [-140, -140],
    [40, -130],
    [-160, 40],
    [20, 30],
  ];

  const heroImages = document.querySelectorAll(".hero-img")

  ScrollTrigger.create({
    trigger: ".hero-section",
    start: "top top",
    end: `+=${window.innerHeight * 6}px`,
    pin: true,
    pinSpacing: true,
    scrub: 1,
    onUpdate: (self) => {
        const progress = self.progress;

        const initialRotations = [5, -3, 3.5, -1];
        const phaseOneStartOffsets = [0, 0.1, 0.2, 0.3];

        heroImages.forEach((img, index) => {
            const initialRotation = initialRotations[index];
            const phase1Start = phaseOneStartOffsets[index];
            const phase1End = Math.min(
                phase1Start + (0.45 - phase1Start) * 0.9,
                0.45
            );

            let x = -50;
            let y, rotation;

            if (progress < phase1Start) {
                y = 200;
                rotation = initialRotation;
            } else if (progress <= 0.45) {
                let phase1Progress;
                
                if (progress >= phase1End) {
                    phase1Progress = 1;
                } else {
                    const linearProgress =
                    (progress - phase1Start) / (phase1End - phase1Start);
                    phase1Progress = 1 - Math.pow(1 - linearProgress, 3);
                }

                y = 200 - phase1Progress * 250;
                rotation = initialRotation;
            } else {
                y = -50;
                rotation = initialRotation;
            }

            const phaseTwoStartOffsets = [0.5, 0.55, 0.6, 0.65];
            const phase2Start = phaseTwoStartOffsets[index];
            const phase2End = Math.min(
                phase2Start + (0.95 - phase2Start) * 0.9,
                0.95
            );

            const finalX = heroImgFinalPos[index][0];
            const finalY = heroImgFinalPos[index][1];

            if(progress >= phase2Start && progress <= 0.95) {
                let phase2Progress;

                if (progress >= phase2End) {
                    phase2Progress = 1;
                } else {
                    const linearProgress = 
                    (progress - phase2Start) / (phase2End - phase2Start);
                    phase2Progress = 1 - Math.pow(1- linearProgress, 3);
                }

                x = -50 + (finalX + 50) * phase2Progress;
                y = -50 + (finalY + 50) * phase2Progress;
                rotation = initialRotation * (1 - phase2Progress);
            } else if (progress > 0.95) {
                x = finalX;
                y = finalY;
                rotation = 0;
            }

            gsap.set(img, {
                transform: `translate(${x}%, ${y}%) rotate(${rotation}deg)`,
            });
        });
    },

  });



ScrollTrigger.create({
  trigger: ".direction-section",
  start: "top top",
  end: "+=250%",
  pin: true,
  pinSpacing: true,
  scrub: 1,  
});





gsap.utils.toArray(".direction-text-reveal").forEach((el) => {
  gsap.to(el, {
    y: "0%", 
    duration: 1.2,
    ease: "power3.out",
    scrollTrigger: {
      trigger: ".direction-section",
      start: "top top",    
      end: "bottom top",
      scrub: true,     
    }
  });
});


gsap.utils.toArray(".card").forEach((el) => {
  gsap.fromTo(el,
    { y: "120px", opacity: 0},
    {
      y: "0px",
      opacity: 1,
      ease: "power3.out",
      scrollTrigger: {
        trigger: el,
        start: "top bottom",  
        end: "bottom top",
        scrub: 1.5,
      }
    }
  );
});

let tl = gsap.timeline({
  scrollTrigger: {
    trigger: ".review-section",
    start: "top top",
    end: "+=300%", // 👈 user scrolls 3 full screens
    pin: true,
    scrub: 1,
  }
});

// 1️⃣ First part of scroll — Text Reveal (0% → 20%)
tl.to(".review-text-reveal", {
  y: "0%",
  ease: "power3.out",
  duration: 1
});

// 2️⃣ WAIT DURING SCROLL (20% → 60%)
// This creates the “2–3 scrolls before cards appear”
tl.to({}, { duration: 1 }); // 👈 Empty tween acts like SCROLL DELAY

// 3️⃣ Then cards appear one by one (60% → 100%)
tl.to(".card-wrapper", {
  y: "0%",
  ease: "power3.out",
  stagger: 0.2,
  duration: 1
});



ScrollTrigger.create({
  trigger: ".contact-section",
  start: "top top",
  end: "+=200%",
  pin: true,
  pinSpacing: true,
  scrub: 1,
});

gsap.utils.toArray(".contact-text-reveal").forEach((el) => {
  gsap.to(el, {
    y: "0%",
    duration: 1.2,
    ease: "power3.out",
    scrollTrigger: {
      trigger: ".contact-section",
      start: "top top",
      end: "bottom top",
      scrub: true,
    }
  });
});


document.addEventListener("DOMContentLoaded", () => {
  gsap.to(".about-reveal", {
    y: "0%",
    duration: 1,
    ease: "power3.out",
    stagger: 0.3,
  });
});

document.addEventListener("DOMContentLoaded", () => {
  gsap.to(".about-p-reveal", {
    y: "0%",
    duration: 1,
    opacity: 1,
    delay: .5,
    ease: "power3.out",
    stagger: 0.3,
  });
});

gsap.registerPlugin(ScrollTrigger);

const textBlocks = document.querySelectorAll("#team-section h4, #team-section p");

gsap.from(textBlocks, {
    scrollTrigger: {
        trigger: "#team-section",
        start: "top top",
        end: "+=1500",
        scrub: true,
        pin: true,
    },
    y: 50,
    opacity: 0,
    stagger: 0.15,
    ease: "power2.out"
});



gsap.registerPlugin(ScrollTrigger);

gsap.to(".wm-text-reveal", {
  y: "0%",
  duration: 1,
  ease: "power2.out",
  stagger: 0.15,
  scrollTrigger: {
    trigger: ".what-matter",
    start: "top top",    // start when section top hits top of viewport
    end: "+=600",        // scroll distance over which animation happens
    scrub: true,
    pin: true            // pins the section while animating
  }
});




gsap.to("#team-section .team-reveal", {
  y: "0%",             // final position
  ease: "power2.out",
  scrollTrigger: {
    trigger: "#team-section",
    start: "top top",   // when top of section reaches 80% of viewport
    end: "+=300",     // animation ends here
    scrub: true
  }
});



// LEFT → RIGHT
gsap.utils.toArray(".animated-hr").forEach((hr) => {
  gsap.fromTo(
    hr,
    { width: "0%", transformOrigin: "left center" },
    {
      width: "100%",
      duration: 1.2,
      ease: "power2.out",
      scrollTrigger: {
        trigger: hr,
        start: "top 80%", // begins when 20% into viewport
        toggleActions: "play none none none",
        scrub: true,
      }
    }
  );
});
gsap.utils.toArray(".animated-hr-right").forEach((hr) => {
  gsap.fromTo(
    hr,
    { scaleX: 0, transformOrigin: "right center" },
    {
      scaleX: 1,
      duration: 1.2,
      ease: "power2.out",
      scrollTrigger: {
        trigger: hr,
        start: "top 80%",
        toggleActions: "play none none none",
        scrub: true,
      }
    }
  );
});

});



document.addEventListener("DOMContentLoaded", () => {
  gsap.to(".about-reveal", {
    y: "0%",
    duration: 1,
    delay: 5,
    ease: "power3.out",
    stagger: 0.3,
  });
});


document.addEventListener("DOMContentLoaded", () => {
  gsap.to(".product-reveal", {
    y: "0%",
    duration: 1,
    delay: 5,
    ease: "power3.out",
    stagger: 0.3,
  });
});

document.addEventListener("DOMContentLoaded", () => {
  gsap.to(".blog-reveal", {
    y: "0%",
    duration: 1,
    delay: 5,
    ease: "power3.out",
    stagger: 0.3,
  });
});


document.addEventListener("DOMContentLoaded", () => {
  gsap.to(".blog-page-reveal", {
    y: "0%",
    duration: 1,
    delay: 5,
    ease: "power3.out",
    stagger: 0.3,
  });
});

document.addEventListener("DOMContentLoaded", () => {
  gsap.to(".about-p-reveal", {
    y: "0%",
    duration: 1,
    opacity: 1,
    delay: 5.5,
    ease: "power3.out",
    stagger: 0.3,
  });
});



  document.addEventListener("DOMContentLoaded", () => {
    gsap.to(".hero-reveal", {
      y: "0%",
      duration: 1,
      ease: "power3.out",
      stagger: 0.3,
      delay: 5,
    });
  });



gsap.to(".loader", {
    scaleY: 0,
    transformOrigin: 'top',
    duration: 1,
    delay: 4
});




      const cards = document.querySelectorAll(".card-wrapper");

      cards.forEach((card) => {
        const slide = card.querySelector(".info-slide");

        // Hover should trigger on the whole card, or only .img-area if you prefer
        card.addEventListener("mouseenter", () => {
          gsap.to(slide, {
            y: "0%",
            duration: 0.6,
            ease: "power3.out",
          });
        });

        card.addEventListener("mouseleave", () => {
          gsap.to(slide, {
            y: "100%",
            duration: 0.6,
            ease: "power3.in",
          });
        });
      });