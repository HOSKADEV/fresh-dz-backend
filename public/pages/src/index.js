// Import data
import { featuresData } from "./data/features.js";
import {  faqData } from "./data/faq.js";

// DOM elements
const menuToggle = document.getElementById('menuToggle');
const closeMenu = document.getElementById('closeMenu');
const mobileMenu = document.getElementById('mobileMenu');
const featuresGrid = document.getElementById("featuresGrid");
const questionsContainer = document.getElementById("questions-container");
const copyRightYear = document.getElementById("date");


// Event listeners for mobile menu toggle
menuToggle.addEventListener('click', () => {
  mobileMenu.classList.remove('hidden');
  closeMenu.classList.remove('hidden');
  menuToggle.classList.add('hidden');
  setTimeout(() => {
    mobileMenu.classList.remove('opacity-0', 'scale-0');
  }, 10);
});

closeMenu.addEventListener('click', () => {
  mobileMenu.classList.add('opacity-0', 'scale-0');
  setTimeout(() => {
    mobileMenu.classList.add('hidden');
    closeMenu.classList.add('hidden');
    menuToggle.classList.remove('hidden');
  }, 500);
});

// Function to render feature cards dynamically
const renderCards = () => {
  featuresData.forEach((feature) => {
    const featureCard = document.createElement("div");
    featureCard.className = "cursor-pointer";
    featureCard.innerHTML = `
      <div class="border-2 relative h-full flex flex-col items-center gap-2 p-4 rounded-md feature">
        <div class="spanHolder p-1 rounded-full">
          ${feature.icon}
        </div>
        <h2 class="mt-2 text-lg font-semibold">${feature.title}</h2>
        <p class="text-secondary px-4 md:px-8 text-sm text-center">${feature.content}</p>
      </div>
    `;
    featuresGrid.appendChild(featureCard);
  });
};

// Function to render questions
const renderQuestions = () => {
  questionsContainer.innerHTML = ""; // Clear existing content

    faqData.forEach((question) => {
    const questionBox = document.createElement("div");
    questionBox.className = `
      flex flex-col p-4 bg-white border-r rounded-lg shadow-slate-200 shadow-lg dark:shadow-mydarkShadow
      w-full md:w-[80%] lg:w-[50%] transition-all
    `;
    questionBox.style.borderRightColor = question.color;
    questionBox.innerHTML = `
      <div class="flex justify-between items-center cursor-pointer gap-4">
        <h3 class="text-sm sm:text-lg ">${question.question}</h3>
        <span style="color: ${question.color};">
          <span chevron-icon style="color: rgb(0, 123, 255);">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em" style="color: #3CAB4F;" xmlns="http://www.w3.org/2000/svg">
              <path d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"></path>
            </svg>
          </span>
        </span>
      </div>
      <p class="pt-8 leading-loose text-secondary dark:text-darksecondary hidden">
        ${question.answer}
      </p>
    `;
    const questionHeader = questionBox.querySelector("div");
    const answerParagraph = questionBox.querySelector("p");
    const chevronIcon = questionBox.querySelector("span[chevron-icon]");
    questionHeader.addEventListener("click", () => {
      const isVisible = !answerParagraph.classList.contains("hidden");
      answerParagraph.classList.toggle("hidden", isVisible);
      chevronIcon.innerHTML = isVisible ? `
        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em"  style="color: #3CAB4F; xmlns="http://www.w3.org/2000/svg">
          <path d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"></path>
        </svg>` : `
        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 448 512" height="1em" width="1em"  style="color: #3CAB4F; xmlns="http://www.w3.org/2000/svg">
          <path d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path>
        </svg>`;
    });
    questionsContainer.appendChild(questionBox);
  });
};

// Function to initialize GSAP animations
const initializeGsapAnimations = () => {
  gsap.from("#title", { opacity: 0, y: -50, duration: 1, delay: 0.2 });
  gsap.from("#description", { opacity: 0, y: 50, duration: 1, delay: 0.4 });
  gsap.from("#downloadButton", { opacity: 0, scale: 0.8, duration: 0.8, delay: 0.6 });
  gsap.from("#social-links .icon", { opacity: 0, scale: 0.8, duration: 0.6, stagger: 0.2, delay: 0.8 });
  gsap.from("#heroImage", { opacity: 0, scale: 0.5, duration: 1, delay: 0.4 });
  gsap.from("#featuresGrid", { opacity: 0, y: 50, duration: 1, delay: 0.4 });
  gsap.from("#featuresTitle", { opacity: 0, scale: 0.8, duration: 1 });
  gsap.from("#featuresSubtitle", { opacity: 0, y: 50, duration: 1, delay: 0.2 });
  gsap.from("#featuresLogo", { opacity: 0, scale: 0.8, duration: 1, delay: 0.4 });
  gsap.from("#whyFresh", { opacity: 0, x: -50, duration: 1, delay: 0.6 });
  gsap.from("#featuresDescription", { opacity: 0, y: 50, duration: 1, delay: 0.8 });
  gsap.from("#faq", { opacity: 0, x: -50, duration: 1, delay: 0.4 });

  const contactTimeline = gsap.timeline({ defaults: { duration: 1, ease: "power3.out" } });
  contactTimeline.from("#contactTitle", { opacity: 0, scale: 0.8 });
  contactTimeline.from("#contactSubtitle", { opacity: 0, y: 50 }, "-=0.5");

  const contactDetails = document.querySelectorAll(".contact-details");
  contactDetails.forEach((detail, index) => {
    contactTimeline.from(detail, { opacity: 0, y: 50, delay: index * 0.2 }, "-=0.8");
  });

};

// Initial render on DOMContentLoaded
document.addEventListener("DOMContentLoaded", () => {
  renderQuestions();
  renderCards();
  copyRightYear.innerText = new Date().getFullYear();
  initializeGsapAnimations();
});


