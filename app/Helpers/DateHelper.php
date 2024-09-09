<?php

namespace App\Helpers;
use Carbon\Carbon;

class DateHelper
{
    public static function formatIndonesianDate($date)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];

        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $timestamp = strtotime($date);
        $dayName = $days[date('l', $timestamp)];
        $day = date('j', $timestamp);
        $monthName = $months[date('F', $timestamp)];
        $year = date('Y', $timestamp);
        return "$dayName $day $monthName $year";
    }

    public static function formatIndonesiaDate($date)
    {

        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $timestamp = strtotime($date);
        $day = date('j', $timestamp);
        $monthName = $months[date('F', $timestamp)];
        $year = date('Y', $timestamp);
        return "$day $monthName $year";
    }
    public static function getStatusText($status)
    {
        switch ($status) {
            case 'S':
                return 'Sakit';
            case 'Tmk':
                return 'Tidak Masuk Kerja';
            case 'Dt':
                return 'Datang Terlambat';
            case 'Pa':
                return 'Pulang Awal';
            case 'Tam':
                return 'Tidak Absen Masuk';
            case 'Tap':
                return 'Tidak Absen Pulang';
            case 'Tjo':
                return 'Tukar Jadwal Off';
            case 'Off':
                return 'Off';
            default:
                return 'Izin';
        }
    }

    public static function formatTimeToPM($time)
    {
        return Carbon::parse($time)->format('g:i A');
    }
}
