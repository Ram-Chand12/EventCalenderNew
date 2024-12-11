<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\golf_club;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $golf_club = golf_club::orderBy('username', 'asc')->paginate(10);
        $colors = array(
            '#c3ff8a', '#86f586','#64bb68', '#bbb845 ','#015310','#33FF57','#683622', '#e6f1db', '#60ffbd', '#d6d571','#f58585'
        );

        return view('dashboard.dashboard', [
            'golf_club' => $golf_club,
            'colors' => $colors,
        ]);
    }
}
