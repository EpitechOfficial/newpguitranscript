<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class DashboardController extends BaseAdminController
{
    /**
     * Show the main admin dashboard
     */
    public function index()
    {
        $stats = $this->getDashboardStats();
        $adminUser = $this->getAdminUser();

        return view('admin.dashboard', compact('stats', 'adminUser'));
    }

    /**
     * Show records for TO role (role 2)
     */
    public function toDashboard()
    {
        $records = $this->getRecordsByStatus(2, 2); // Status 2 records for TO role
        $adminUser = $this->getAdminUser();
        //dd($adminUser);

        return view('admin.dashboardto', compact('records', 'adminUser'));
    }

    /**
     * Show records for KI role (role 3)
     */
    public function kiDashboard()
    {
        $records = $this->getRecordsByStatus(3, 3); // Status 3 records for KI role
        $adminUser = $this->getAdminUser();

        return view('admin.dashboard_ki', compact('records', 'adminUser'));
    }

    /**
     * Show records for PO role (role 4)
     */
    public function poDashboard()
    {
        $records = $this->getRecordsByStatus(4, 4); // Status 4 records for PO role
        $adminUser = $this->getAdminUser();

        return view('admin.dashboard_po', compact('records', 'adminUser'));
    }

    /**
     * Show records for FO role (role 5)
     */
    public function foDashboard()
    {
        $records = $this->getRecordsByStatus(5, 5); // Status 5 records for FO role
        $adminUser = $this->getAdminUser();

        return view('admin.dashboard_fo', compact('records', 'adminUser'));
    }

    /**
     * Show records for Transreceive role (role 6)
     */
    public function transreceiveDashboard()
    {
        $records = $this->getRecordsByStatus(0, 0); // Status 6 records for Transreceive role
        $adminUser = $this->getAdminUser();

        return view('admin.transrecevedashboard', compact('records', 'adminUser'));
    }

    /**
     * Show processed records (role 7)
     */
    public function recordProcessed()
    {
        $records = $this->getRecordsByStatus(7, 7); // Status 7 records for Record Officer role
        $adminUser = $this->getAdminUser();

        return view('admin.recordProcessed', compact('records', 'adminUser'));
    }
} 