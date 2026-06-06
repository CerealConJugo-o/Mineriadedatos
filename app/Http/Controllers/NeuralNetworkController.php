<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class NeuralNetworkController extends Controller
{
    public function index()
    {
        $total = DB::table('registro_a')->count();

        $murieron = DB::table('registro_a')
            ->where('mortalidad', 1)
            ->count();

        $vivieron = DB::table('registro_a')
            ->where('mortalidad', 0)
            ->count();

        return view(
            'algorithms.neural',
            compact(
                'total',
                'murieron',
                'vivieron'
            )
        );
    }
}