<?php
/*
Plugin Name: Auto Submit Formidable
Description: Tilføjer automatisk auto-submit for Formidable checkbox-felter, men KUN på sider hvor shortcoden [auto_submit_formidable] bruges.
Version: 1.0
Author: https://github.com/uldtot/
*/

if (!defined('ABSPATH')) exit;

// -----------------------------------------------------------
// SHORTCODE DER INDSÆTTER SCRIPTET
// -----------------------------------------------------------
add_shortcode('auto_submit_formidable', function () {

    ob_start();
    ?>

    <script>
    (function () {

        // Vent til DOM er klar
        document.addEventListener("DOMContentLoaded", function () {

            // Find alle Formidable forms
            const forms = document.querySelectorAll('form.frm-show-form');
            if (!forms.length) {
                console.warn("Ingen Formidable forms fundet på denne side.");
                return;
            }

            forms.forEach(function (form) {

                const submitBtn = form.querySelector('.frm_final_submit');
                if (!submitBtn) {
                    console.warn("Submit-knap ikke fundet i formularen.");
                    return;
                }

                let autoSubmitEnabled = false;

                // Giv Formidable tid til at sætte URL-parametre (anti-loop)
                setTimeout(() => {
                    autoSubmitEnabled = true;
                }, 600);

                // Event delegation på ændringer i checkboxe
                form.addEventListener('change', function (e) {
                    const target = e.target;

                    if (target && target.type === "checkbox") {

                        if (!autoSubmitEnabled) {
                            console.log('Ignorerer ændring under initial load:', target.id);
                            return;
                        }

                        console.log('Autosubmitter grundet checkbox ændring:', target.id);

                        // Brug Formidables egen valideringsflow
                        submitBtn.click();
                    }
                });
            });

        });

    })();
    </script>

    <?php
    return ob_get_clean();
});
