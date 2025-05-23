<?php 

require_once '../URI.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        submitData();

        async function submitData() {
            const queryString = window.location.search;
            const targetUrl = `http://localhost/artbook/api/google_callback.php${queryString}`;
            const targetUrl = `${SITE_URL}/api/google_callback.php${queryString}`;

            try {
                const response = await fetch(targetUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });


                if (response.ok) {

                    const data = await response.json();
                    // Save user ID to localStorage if it exists in response
                    if (data.user_id) {
                        localStorage.setItem('artbook_user_id', data.user_id);

                        // Optionally save other user data
                        if (data.name) localStorage.setItem('ser_name', data.name);
                        if (data.email) localStorage.setItem('artbook_user_email', data.email);
                    }
                    window.location.href = 'http://localhost/artbook/index.php';
                    window.location.href = "<?= SITE_URL ?>/index.php";

                } else {
                    window.location.href = 'http://localhost/artbook/index.php';
                    window.location.href = "<?= SITE_URL ?>/index.php";
                }
            } catch (error) {
                window.location.href = 'http://localhost/artbook/index.php';
                window.location.href = "<?= SITE_URL ?>/index.php";
            }
        }

    });

</script>