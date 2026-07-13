<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use App\Traits\ApiLogger;
use App\Traits\ApiResponser;

class DashboardController extends Controller
{
    use ApiLogger, ApiResponser;

    protected $dashboardService;

    public function __construct(
        DashboardService $dashboardService
    ) {
        $this->dashboardService = $dashboardService;
    }

    public function getDashboard()
    {
        try {
            $data = $this->dashboardService->getDashboard();

            return $this->success(
                'Get dashboard successfully',
                $data,
                200
            );
        } catch (\Throwable $th) {
            $this->logError('Get dashboard failed: ', $th);

            return $this->error(
                'Get dashboard failed',
                400,
                $th->getMessage()
            );
        }
    }
}
