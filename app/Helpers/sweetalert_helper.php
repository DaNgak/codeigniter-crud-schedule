<?php

if (!function_exists('showSweetAlert')) {
    function showSweetAlert()
    {
        if (session()->getFlashdata('message')) {
            $alertType = session()->getFlashdata('message')['type'];
            $iconType = 'warning';  // Default to warning

            if ($alertType === 'success') {
                $iconType = 'success';
            } elseif ($alertType === 'danger') {
                $iconType = 'error';
            }

            echo "<script>
                    Swal.fire({
                        title: '" . session()->getFlashdata('message')['title'] . "',
                        text: '" . session()->getFlashdata('message')['data'] . "',
                        icon: '$iconType'
                    });
                </script>";
        }
    }
}
