<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $path = base_path('famindo.html');

    if (! file_exists($path)) {
        return 'File landing page (famindo.html) belum tersedia di root folder.';
    }

    // Baca file asli
    $html = file_get_contents($path);

    // HTML Tombol Login yang akan disisipkan
    // Kita gunakan style inline untuk memastikan tampilan oke karena kita tidak ubah CSS asli
    $loginButton = '
        <li class="nav-item" style="margin-left: 15px;">
            <a class="nav-link btn btn-primary text-white" href="/admin/login" style="padding: 8px 20px; border-radius: 5px; font-weight: bold; background-color: #0d6efd !important; color: white !important;">LOGIN SYSTEM</a>
        </li>';

    // Cari penutup list item terakhir (menu Contact) dan sisipkan tombol sebelum penutup ul
    // Pola ini mencari menu Contact yang ada di file famindo.html
    $search = 'href=#contact>Contact</a>';
    $replace = 'href=#contact>Contact</a></li>'.$loginButton;

    // Lakukan penyuntikan
    $modifiedHtml = str_replace($search, $replace, $html);

    return $modifiedHtml;
});
