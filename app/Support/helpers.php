<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka): string
    {
        return 'Rp ' . number_format($angka ?? 0, 2, ',', '.');
    }
}

if (!function_exists('formatTanggalID')) {
    function formatTanggalID($date): string
    {
        return \Carbon\Carbon::parse($date)->translatedFormat('d F Y');
    }
}
