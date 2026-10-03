<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Reports & Analytics hub: a simple, role-filtered list of report links
     * grouped by category. Each report renders and filters its own data —
     * this page holds no report data itself.
     */
    public function index(): View
    {
        return view('reports.index');
    }
}
