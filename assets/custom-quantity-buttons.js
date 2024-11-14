document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(event) {
            if (event.target.matches('button.plus, button.minus')) {
                let clickedButton = event.target;
                let parent = clickedButton.parentNode;
                let input = parent.querySelector('input');
                let qty = parseInt(input.value);
                let min = parseInt(input.getAttribute('min')) || 0;
                let max = parseInt(input.getAttribute('max')) || Infinity;
                let step = parseInt(input.getAttribute('step')) || 1;
                let updateCartButton = document.querySelector('[name="update_cart"]');

                if (clickedButton.classList.contains('plus')) {
                    if (max && max <= qty) {
                        input.value = max;
                    } else {
                        input.value = qty + step;
                    }
                } else if (clickedButton.classList.contains('minus')) {
                    if (min && min >= qty) {
                        input.value = min;
                    } else if (qty > 1) {
                        input.value = qty - step;
                    }
                }

                if (updateCartButton) { // Проверка на null
                    updateCartButton.removeAttribute('disabled');
                }
            }
        });
    });