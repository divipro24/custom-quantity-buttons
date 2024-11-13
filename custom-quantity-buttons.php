<?php
/*
Plugin Name: Custom Quantity Buttons
Plugin URI:  https://divipro24.com/
Description: Меняет кнопки + и - на карточке товара в Woocommerce
Author URI: https://divipro24.com
Plugin URI: https://divipro24.com
Version: 1.0.0
Author: Dmitri Andrejev
Github URI: https://github.com/divipro24/
License: GPLv2
 */

// Добавление кнопок + и - вокруг поля ввода количества
add_action( 'woocommerce_before_quantity_input_field', 'aleks_quantity_minus', 25 );
add_action( 'woocommerce_after_quantity_input_field', 'aleks_quantity_plus', 25 );

function aleks_quantity_plus() {
    global $product;
    if ( $product && $product->is_sold_individually() ) {
        return; // Ничего не выводим, если товар продается в одном экземпляре
    }
    echo '<button type="button" class="plus">+</button>';
}

function aleks_quantity_minus() {
    global $product;
    if ( $product && $product->is_sold_individually() ) {
        return; // Ничего не выводим, если товар продается в одном экземпляре
    }
    echo '<button type="button" class="minus">-</button>';
}

// Добавление JavaScript для управления количеством товаров
function custom_quantity_buttons_js() {
    ?>
    <script>
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
    </script>
    <?php
}
add_action( 'wp_footer', 'custom_quantity_buttons_js' );

// Добавление CSS из отдельного файла
function custom_quantity_buttons_enqueue_styles() {
    wp_enqueue_style(
        'custom-quantity-buttons-style',
        plugin_dir_url(__FILE__) . 'assets/style.css',
        array(),
        '1.0',
        'all'
    );
}
add_action( 'wp_enqueue_scripts', 'custom_quantity_buttons_enqueue_styles' );

require 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/divipro24/custom-quantity-buttons',
    __FILE__,
    'custom-quantity-buttons'
);

$myUpdateChecker->setBranch('main');