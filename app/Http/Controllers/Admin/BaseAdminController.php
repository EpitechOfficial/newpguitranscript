<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransDetailsNew;
use App\Models\TransInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BaseAdminController extends Controller
{
    protected $adminUser;
    protected $recordsPerPage = 20;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Get the authenticated admin user from session
     */
    protected function getAdminUser()
    {
        if (!$this->adminUser) {
            // Get admin user from session (same approach as original AdminLoginController)
            $adminUsername = session('admin_username');
            if ($adminUsername) {
                $this->adminUser = \App\Models\AdminUser::where('username', $adminUsername)->first();
            }
            
            // Fallback to Auth guard if session approach doesn't work
            if (!$this->adminUser) {
                $this->adminUser = Auth::guard('admin')->user();
            }
        }
        
        return $this->adminUser;
    }

    /**
     * Get records based on status and role
     */
    protected function getRecordsByStatus($status, $role = null)
    {
        $query = TransDetailsNew::where('status', $status)
            ->whereRaw('email REGEXP "^[0-9]+$"')
            ->whereHas('transInvoice', function ($query) {
                $query->whereColumn('amount_charge', 'amount_paid');
            })
            ->with([
                'transInvoice' => function ($query) {
                    $query->select('invoiceno', 'purpose', 'dy', 'mth', 'cheque');
                },
                'transDetailsFiles:id,trans_details_id,file_path',
                'couriers' => function ($query) {
                    $query->select('trans_details_id', 'courier_name', 'destination', 'address', 'address2', 'transcript_purpose', 'number_of_copies');
                }
            ]);

        // Apply role-based filtering if needed
        if ($role) {
            $query = $this->applyRoleFilter($query, $role, $status);
        }

        return $query->orderBy('date_requested', 'desc')
                    ->paginate($this->recordsPerPage);
    }

    /**
     * Apply role-based filtering to queries
     */
    protected function applyRoleFilter($query, $role, $status)
    {
        switch ($role) {
            case 2: // TO role - Group by matric
                // For TO role, get only the most recent record per matric number
                $query->select('*')
                      ->whereIn('id', function($subquery) use ($status) {
                          $subquery->select(DB::raw('MAX(id)'))
                                   ->from('trans_details_new')
                                   ->where('status', $status)
                                   ->groupBy('matric');
                      });
                break;
            case 3: // KI role
                $query->select('*')
                      ->whereIn('id', function($subquery) use ($status) {
                          $subquery->select(DB::raw('MAX(id)'))
                                   ->from('trans_details_new')
                                   ->where('status', $status)
                                   ->groupBy('matric');
                      });
                break;
            case 4: // PO role
                // Add specific filtering for PO role
                break;
            case 5: // FO role
                $query->select('*')
                      ->whereIn('id', function($subquery) use ($status) {
                          $subquery->select(DB::raw('MAX(id)'))
                                   ->from('trans_details_new')
                                   ->where('status', $status)
                                   ->groupBy('matric');
                      });
                break;
            case 6: // Transreceive role
                $query->select('*')
                      ->whereIn('id', function($subquery) use ($status) {
                          $subquery->select(DB::raw('MAX(id)'))
                                   ->from('trans_details_new')
                                   ->where('status', $status)
                                   ->groupBy('matric');
                      });
                break;
            case 7: // Record Processed role
                
                break;
        }

        return $query;
    }

    /**
     * Get record counts for dashboard
     */
    protected function getDashboardStats()
    {
        return [
            'pending' => TransDetailsNew::where('status', 0)->count(),
            'processing' => TransDetailsNew::where('status', 1)->count(),
            'completed' => TransDetailsNew::where('status', 2)->count(),
            'total' => TransDetailsNew::count(),
        ];
    }


} 