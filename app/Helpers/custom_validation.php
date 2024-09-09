<?php

if (!function_exists('valid_tahun_akhir')) {
    function valid_tahun_akhir($tahun_akhir, $tahun_awal)
    {
        // Cek jika tahun_akhir lebih kecil dari tahun_awal
        return $tahun_akhir >= $tahun_awal;
    }
}