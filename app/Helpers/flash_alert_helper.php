<?php

if (!function_exists('showSweetAlert')) {
    function showSweetAlert() {
        if (session()->getFlashdata('message')) {
            $alertType = session()->getFlashdata('message')['type'];
            $iconType = 'error';  // Default to error

            if ($alertType === 'success') {
                $iconType = 'success';
            } elseif ($alertType === 'warning') {
                $iconType = 'warning';
            }

            echo "<script>
                Swal.fire({
                    title: '" . session()->getFlashdata('message')['title'] . "',
                    text: '" . session()->getFlashdata('message')['description'] . "',
                    icon: '$iconType'
                });
            </script>";
        }
    }
}

if (!function_exists('showAlertBs')) {
    function showAlertBs() {
        if (session()->getFlashdata('message')) {
            $alertType = session()->getFlashdata('message')['type'];
            $alertTitle = session()->getFlashdata('message')['title'];
            $alertDescription = session()->getFlashdata('message')['description'];

            echo "<div class=\"alert alert-$alertType alert-dismissible fade show mb-4\" role=\"alert\" style=\"font-size: 0.875rem;\">
                <strong>$alertTitle</strong><br/> $alertDescription
                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                    <span aria-hidden=\"true\">&times;</span>
                </button>
            </div>";
        }
    }
}

