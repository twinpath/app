<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function lineChart()
    {
        return view('pages.chart.line-chart', ['title' => 'Line Chart']);
    }

    public function barChart()
    {
        return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
    }
}
