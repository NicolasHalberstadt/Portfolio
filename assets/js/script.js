$(document).ready(function () {
    var $cursor = $("#cursor");
    var initCursor = false;
    $(window).on("mousemove", function (e) {
        var mouseX = e.clientX;
        var mouseY = e.clientY;
        if (!initCursor) {
            TweenLite.to($cursor, 0.3, {
                opacity: 1
            });
            initCursor = true;
        }
        TweenLite.to($cursor, 0, {
            top: mouseY + "px",
            left: mouseX + "px"
        });
    });

    $(window).on("mouseout", function () {
        TweenLite.to($cursor, 0.3, {
            opacity: 0
        });
        initCursor = false;
    });

    $('.nav-trigger').click(function () {
        $(this).toggleClass('active');
        $(".main_list").toggleClass("show_list");
    });

    $('.main_list .navlinks li a').on('click', function () {
        $('.nav-trigger').toggleClass('active');
        $(".main_list").toggleClass("show_list");
    })

    // Contact button GSAP animation 
    class Button {
        constructor(buttonElement) {
            this.block = buttonElement;
            this.init();
            this.initEvents();
        }

        init() {
            const el = gsap.utils.selector(this.block);

            this.DOM = {
                button: this.block,
                flair: el(".button__flair")
            };

            this.xSet = gsap.quickSetter(this.DOM.flair, "xPercent");
            this.ySet = gsap.quickSetter(this.DOM.flair, "yPercent");
        }

        getXY(e) {
            const {
                left,
                top,
                width,
                height
            } = this.DOM.button.getBoundingClientRect();

            const xTransformer = gsap.utils.pipe(
                gsap.utils.mapRange(0, width, 0, 100),
                gsap.utils.clamp(0, 100)
            );

            const yTransformer = gsap.utils.pipe(
                gsap.utils.mapRange(0, height, 0, 100),
                gsap.utils.clamp(0, 100)
            );

            return {
                x: xTransformer(e.clientX - left),
                y: yTransformer(e.clientY - top)
            };
        }

        initEvents() {
            this.DOM.button.addEventListener("mouseenter", (e) => {
                const { x, y } = this.getXY(e);

                this.xSet(x);
                this.ySet(y);

                gsap.to(this.DOM.flair, {
                    scale: 1,
                    duration: 0.4,
                    ease: "power2.out"
                });
            });

            this.DOM.button.addEventListener("mouseleave", (e) => {
                const { x, y } = this.getXY(e);

                gsap.killTweensOf(this.DOM.flair);

                gsap.to(this.DOM.flair, {
                    xPercent: x > 90 ? x + 20 : x < 10 ? x - 20 : x,
                    yPercent: y > 90 ? y + 20 : y < 10 ? y - 20 : y,
                    scale: 0,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            this.DOM.button.addEventListener("mousemove", (e) => {
                const { x, y } = this.getXY(e);

                gsap.to(this.DOM.flair, {
                    xPercent: x,
                    yPercent: y,
                    duration: 0.4,
                    ease: "power2"
                });
            });
        }
    }

    const buttonElements = document.querySelectorAll('[data-block="button"]');

    buttonElements.forEach((buttonElement) => {
        new Button(buttonElement);
    });


    $('.tabs-switcher button').on('click', function () {
        if (!$(this).hasClass('active')) {
            $('.tabs-switcher button').removeClass('active');
            $(this).addClass('active');
            const targetDiv = $(this).attr('id');
            $('.tabs .tab').removeClass('active');
            $('.tabs .tab#' + targetDiv).addClass('active');
        }
    });

    if ($(window).width() <= 768) {
        $('.fold-btn').on('click', function () {
            $(`.${$(this).attr('id')}`).toggle(300);
            if ($(this).find('.fa-solid').hasClass('fa-chevron-up')) {
                $(this).find('span').html('<i class="fa-solid fa-chevron-down"></i>');
            } else {
                $(this).find('span').html('<i class="fa-solid fa-chevron-up"></i>');
            }
        })
    }

    $('#contact form #contact_message').on('input', function (e) {
        let textLength = $(this).val().length;
        $('#charCount #count').html(textLength);
        if (textLength >= 50) {
            $('#charCount').css('color', '#419187');
        } else {
            $('#charCount').css('color', 'lightgrey');
        }
    });

    $('#contact form').on('submit', function (e) {
        e.preventDefault();

        let $form = $(this);
        let url = $form.attr('action');
        if (!url.startsWith('https://')) {
            url = 'https://nicolashalberstadt.com' + url;
        }
        console.log(url); // Devrait afficher : "https://nicolashalberstadt.com/contact"
        let formData = $form.serialize();
        $('#contact_submit').html('<i class="fa-solid fa-spinner fa-spin"></i>');
        setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $form[0].reset();
                        $form.css('opacity', '0');
                        $form.find('input, textarea, button').prop('disabled', true);
                        $('#successMessage .message-container').text(response.message);
                        $('#successMessage').fadeIn();
                    } else if (response.status === 'error') {
                        $('#contact_submit').html('Envoyer');

                        $form.find('.error-message').remove();
                        $.each(response.errors, function (fieldName, errorMessage) {
                            let $field = $form.find(`#contact_${fieldName}`);
                            if ($field.length > 0) {
                                $field.after(`<span class="error-message text-danger">${errorMessage}</span>`);
                            }
                        });
                    }
                },
                error: function (xhr, status, error) {
                    $('#contact_submit').html('Envoyer');
                    $form.css('opacity', '1');
                },
            });
        }, 1000);
    });
});


