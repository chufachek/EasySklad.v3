<?php
class DashboardController
{
    private $dashboard;

    public function __construct()
    {
        $this->dashboard = new DashboardService();
    }

    public function page()
    {
        AuthMiddleware::ensure();
        Helpers::view('app/dashboard');
    }

    public function stats($params)
    {
        AuthMiddleware::ensure();
        $input = Request::input();
        $companyId = isset($input['companyId']) ? $input['companyId'] : null;
        $warehouseId = isset($input['warehouseId']) ? $input['warehouseId'] : null;
        $range = isset($input['range']) ? $input['range'] : '7d';
        $data = $this->dashboard->stats($companyId, $warehouseId, $range);
        return Response::json(array('ok' => true, 'data' => $data));
    }
}
