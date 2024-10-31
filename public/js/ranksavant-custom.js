document.addEventListener(
    'DOMContentLoaded',
    () => {
        const containers = document.querySelectorAll('.ranksavant-container');
        containers.forEach(container => {
            //Images
            const images = container.querySelectorAll('.rks-bottom-gallery-open-modal');
            images.forEach(
                image => {
                        image.addEventListener(
                            'click',
                            function (event) {
                                const modalContent = event.target.parentNode.parentNode.querySelector('.rks-slider-content').cloneNode(true);
                                modalContent.style.display = 'block';
                                createModal(modalContent, container.dataset.color);
                            }
                        )
                }
            );
        });
    }
);

//Show popup based on url, make sure it runs only once
if ( ! window.ranksavantUrlCheckRun) {
    window.ranksavantUrlCheckRun = true;
    document.addEventListener(
        'DOMContentLoaded',
        () => {
            const postValue = getURLParameter('ranksavant-post');
            // Attempt to convert parameters to integers
            const postNumber = parseInt(postValue, 10);
            // Find the element based on 'data-ranksavant-id' attribute matching the parameter value
            let modalElement;
            let color = '#000';
            if ( ! isNaN(postNumber)) {
                let posts = document.querySelectorAll(`[data-ranksavant-id = "${postNumber}"]`);
                if (posts.length > 0) {
                    modalElement = posts[0].cloneNode(true);
                    color = posts[0].closest('.ranksavant-container').dataset.color;
                }
            }
            // If a relevant element has been found and cloned, display and create the modal
            if (modalElement) {
                modalElement.style.display = 'block';
                createModal(modalElement, color);
            }
        }
    )
}

function createModal(modalContent, color) {
    // Create the modal container
    const modal = document.createElement('div');
    modal.setAttribute('class', 'rks-modal');

    // Create the modal content container
    const modalContentContainer = document.createElement('div');
    modalContentContainer.setAttribute('class', 'rks-modal-container');

    // Check if the content is a string of HTML, a text node, or an HTMLElement
    if (typeof modalContent === 'string' || modalContent instanceof String) {
        // If it's a string, set it as innerHTML in case it contains HTML tags
        modalContentContainer.innerHTML = modalContent;
    } else if (modalContent instanceof HTMLElement) {
        // If it's an HTMLElement, append it directly
        modalContentContainer.appendChild(modalContent);
    } else {
        // Otherwise, treat it as plain text
        modalContentContainer.textContent = modalContent;
    }

    // Create a simple close button
    const closeButton = document.createElement('button');
    closeButton.setAttribute('class', 'rks-close');
    closeButton.innerHTML = '<svg width="102" height="102" viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d_1095_11664)"><circle cx="51" cy="47" r="30.5" stroke="#EBEBEB" fill="#fff"/></g><path d="M52.2828 47.0001L57.7342 41.5485C58.089 41.1939 58.089 40.6206 57.7342 40.266C57.3796 39.9113 56.8063 39.9113 56.4517 40.266L51.0001 45.7176L45.5486 40.266C45.1939 39.9113 44.6207 39.9113 44.2661 40.266C43.9113 40.6206 43.9113 41.1939 44.2661 41.5485L49.7175 47.0001L44.2661 52.4517C43.9113 52.8063 43.9113 53.3797 44.2661 53.7343C44.4428 53.9111 44.6752 54 44.9074 54C45.1395 54 45.3717 53.9111 45.5486 53.7343L51.0001 48.2827L56.4517 53.7343C56.6286 53.9111 56.8608 54 57.0929 54C57.3251 54 57.5573 53.9111 57.7342 53.7343C58.089 53.3797 58.089 52.8063 57.7342 52.4517L52.2828 47.0001Z" fill="' + color + '"/><defs><filter id="filter0_d_1095_11664" x="0" y="0" width="102" height="102" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"/><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/><feOffset dy="4"/><feGaussianBlur stdDeviation="10"/><feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.15 0"/><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_1095_11664"/><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_1095_11664" result="shape"/></filter></defs></svg>';

    // Add an event listener to the close button to remove the modal from the document
    closeButton.addEventListener(
        'click',
        function () {
            document.body.removeChild(modal);
        }
    );

    //Add event listener to prev/next arrows
    modalContent.addEventListener(
        'click',
        function (event) {
            // Use closest to find the nearest parent element with class 'rks-prev' or 'rks-next'
            const target = event.target.closest('.rks-prev, .rks-next');
            // Check if the clicked element or its parent is one of the buttons
            if (target) {
                // Determine the direction based on the clicked button
                const direction = event.target.classList.contains('rks-prev') ? -1 : 1;
                const sliderContainer = event.target.closest('.rks-slider-content');
                rksShowSlides(direction, sliderContainer);
            }
        }
    );

    // Append the close button to the modal content
    modalContentContainer.appendChild(closeButton);

    // Append the modal content to the modal container
    modal.appendChild(modalContentContainer);

    // Append the modal container to the end of the body
    document.body.appendChild(modal);
}

function rksShowSlides(n, slides_parent) {
    let slides = slides_parent.querySelectorAll('.rks-slides');
    let slideIndex = 0;
    for (let j = 0; j < slides.length; j++) {
        if ('block' === slides[j].style.display) {
            slideIndex = j;
            slideIndex += 1;
            break;
        }
    }
    n += slideIndex;
    let i;
    if (n > slides.length) {
        n = 1}
    if (n < 1) {
        n = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = 'none';
    }
    slides[n - 1].style.display = 'block';
    slides_parent.querySelector('.rks-caption').innerText = n + ' of ' + slides.length;
}


function getURLParameter(name) {
    const results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results !== null ? decodeURI(results[1]) : null;
}


document.addEventListener(
    "DOMContentLoaded",
    () => {
        let ranksavantContainerElelements = document.querySelectorAll('.ranksavant-container');
        ranksavantContainerElelements.forEach(ranksavantContainerEl => {
            const slideshows = ranksavantContainerEl.querySelectorAll('.ranksavant-posts-container-slider');
            const slideIndex = Array(slideshows.length).fill(1);
            slideshows.forEach(
                (slideshow, index) => {
                    showSlides(1, index);
                }
            );
            function plusSlides(n, no) {
                showSlides(slideIndex[no] += n, no);
            }
            function showSlides(n, no) {
                const slides = slideshows[no].getElementsByClassName('ranksavant-slides-item');
                const dots = ranksavantContainerEl.querySelectorAll(`.ranksavant-dot-container[data-slideshow = "${no}"] .ranksavant-layoult-slider-dot`);
                if (n > slides.length) {
                    slideIndex[no] = 1 }
                if (n < 1) {
                    slideIndex[no] = slides.length }
                for (let slide of slides) {
                    slide.style.display = "none";
                }
                for (let dot of dots) {
                    dot.classList.remove('ranksavant-layoult-slider-dot-active');
                    dot.style.backgroundColor = "";
                }
                slides[slideIndex[no] - 1].style.display = "flex";
                dots[slideIndex[no] - 1].classList.add('ranksavant-layoult-slider-dot-active');
                dots[slideIndex[no] - 1].style.backgroundColor = ranksavantContainerEl.dataset.color;
            }
            ranksavantContainerEl.querySelectorAll('.ranksavant-slider-layoult-prev').forEach(
                button => {
                    button.addEventListener(
                    'click',
                    () => {
                            plusSlides(-1, button.getAttribute('data-slideshow'));
                    }
                );
                }
            );
            ranksavantContainerEl.querySelectorAll('.ranksavant-slider-layoult-next').forEach(
                button => {
                    button.addEventListener(
                    'click',
                    () => {
                            plusSlides(1, button.getAttribute('data-slideshow'));
                    }
                );
                }
            );
            ranksavantContainerEl.querySelectorAll('.ranksavant-layoult-slider-dot').forEach(
                dot => {
                    dot.addEventListener(
                    'click',
                    () => {
                            const slideshowNo = dot.parentElement.getAttribute('data-slideshow');
                            const slideNo = dot.getAttribute('data-slide');
                            showSlides(slideIndex[slideshowNo] = parseInt(slideNo), slideshowNo);
                    }
                );
                }
            );
        })
    }
);
document.addEventListener(
    "DOMContentLoaded",
    () => {
    class Carousel {
        constructor(root) {
            this.root = root;
            this.carouselInner = root.querySelector('.ranksavant-carousel-inner');
            this.prevButton = root.querySelector('[data-ranksavant-prev]');
            this.nextButton = root.querySelector('[data-ranksavant-next]');
            this.dotsContainer = root.querySelector('[data-ranksavant-dots]');
            this.items = this.carouselInner.children;
            this.currentIndex = 0;

            this.createDots();
            this.update();

            this.prevButton.addEventListener('click', () => this.prev());
            this.nextButton.addEventListener('click', () => this.next());
            this.dots.forEach(
            (dot, index) => {
                dot.addEventListener('click', () => this.goTo(index));
            }
            );

            window.addEventListener('resize', () => this.update());
        }

        createDots() {
            this.dotsContainer.innerHTML = '';
            const numberOfDots = Math.ceil(this.items.length / this.itemsPerView());
            for (let i = 0; i < numberOfDots; i++) {
                const dot = document.createElement('span');
                dot.classList.add('carousel-ranksavant-dot');
                dot.setAttribute('data-ranksavant-dot', '');
                this.dotsContainer.appendChild(dot);
            }
            this.dots = Array.from(this.dotsContainer.children);
        }

        itemsPerView() {
            return window.innerWidth >= 768 ? 3 : 1;
        }

        update() {
            const itemWidth = this.items[0].getBoundingClientRect().width;
            this.carouselInner.style.transform = `translateX(-${this.currentIndex * itemWidth}px)`;
            this.dots.forEach(dot => dot.classList.remove('ranksavant-carousel-dot-active'));
            this.dots[Math.floor(this.currentIndex / this.itemsPerView())].classList.add('ranksavant-carousel-dot-active');
            this.prevButton.classList.toggle('ranksavant-carousel-arrow-disabled', this.currentIndex === 0);
            this.nextButton.classList.toggle('ranksavant-carousel-arrow-disabled', this.currentIndex >= this.items.length - this.itemsPerView());
        }

        next() {
            if (this.currentIndex < this.items.length - this.itemsPerView()) {
                this.currentIndex++;
                this.update();
            }
        }

        prev() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
                this.update();
            }
        }

        goTo(index) {
            this.currentIndex = index * this.itemsPerView();
            this.update();
        }
    }
    document.querySelectorAll('[data-ranksavant-carousel]').forEach(root => new Carousel(root));
    }
)