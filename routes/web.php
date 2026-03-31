<?php

use Illuminate\Support\Facades\Route;

/* DASHBOARD */
Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

/* DATA LANSIA */
Route::get('/lansia', function () {
    return view('lansia');
});

/* KRITERIA */
Route::get('/kriteria', function () {
    return view('kriteria');
});

/* PENILAIAN */
Route::get('/penilaian', function () {
    return view('penilaian');
});

/* PERHITUNGAN */
Route::get('/perhitungan', function () {
    return view('perhitungan');
});

/* HASIL PRIORITAS */
Route::get('/hasil', function () {
    return view('hasil');
});

/* PENYALURAN BANTUAN */
Route::get('/bantuan', function () {
    return view('bantuan');
});

/* MONITORING */
Route::get('/monitoring', function () {
    return view('monitoring');
});
