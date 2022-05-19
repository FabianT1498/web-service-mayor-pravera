<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Models\CashRegisterData;

class CashRegisterRepository implements CashRegisterRepositoryInterface
{

    public function getTotalsFromSafact($start_date, $end_date, $user = null){
        /* Consulta para obtener los totales de las facturas*/
        $date_params = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $user_params = $user
          ? " = '" . $user . "'"
          : "IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY')";

        $interval_query = ($start_date === $end_date)
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        $factors = DB::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("ROUND(MAX(SAFACT.Factor), 2) as MaxFactor, CAST(SAFACT.FechaE as date) as FechaE")
            ->whereRaw($interval_query, $date_params)
            ->groupByRaw("CAST(SAFACT.FechaE as date)");

        return DB
            ::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("MAX(SAFACT.CodUsua) AS CodUsua, CAST(ROUND(SUM(SAFACT.CancelE * SAFACT.Signo), 2) AS decimal(18, 2))  AS bolivares,
            CAST(ROUND((SUM(SAFACT.CancelE * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2))  AS bolivaresADolares,
            CAST(ROUND((SUM(SAFACT.CancelC * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2))  AS dolares,
            CAST(ROUND(SUM(SAFACT.Credito * SAFACT.Signo), 2) AS decimal(18, 2)) AS credito,
            CAST(ROUND((SUM(SAFACT.Credito * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2))  AS creditoADolares,
            CAST(ROUND(MAX(FactorHist.MaxFactor), 2) AS decimal(18, 2)) as Factor,
            CAST(SAFACT.FechaE as date) as FechaE")
            ->joinSub($factors, 'FactorHist', function($query){
                $query->on(DB::raw("CAST(SAFACT.FechaE AS date)"), '=', "FactorHist.FechaE");
            })
            ->whereRaw("SAFACT.CodUsua " . $user_params .  " AND " . $interval_query,
                $date_params)
            ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE as date)")
            ->orderByRaw("SAFACT.CodUsua asc, CAST(SAFACT.FechaE as date)")
            ->get();
    }

    public function getTotalsEPaymentMethods($start_date, $end_date, $user = null){

        /* Consulta para obtener los totales de las facturas*/
        $date_params = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date)
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        $user_params = $user
          ? " = '" . $user . "'"
          : "IN ('CAJA1', 'CAJA2', 'CAJA3', 'CAJA4', 'CAJA5',
                'CAJA6' , 'CAJA7', 'DELIVERY')";

        $factors = DB::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("ROUND(MAX(SAFACT.Factor), 2) as MaxFactor, CAST(SAFACT.FechaE as date) as FechaE")
            ->whereRaw($interval_query, $date_params)
            ->groupByRaw("CAST(SAFACT.FechaE as date)");

        return DB
        ::connection('saint_db')
        ->table('SAIPAVTA')
        ->selectRaw("
            MAX(SAFACT.CodUsua) as CodUsua, MAX(SAIPAVTA.CodPago) as CodPago, MAX(CAST(SAFACT.FechaE as date)) as FechaE,
            CAST(ROUND(SUM(SAIPAVTA.Monto * SAFACT.Signo), 2) AS decimal(18, 2)) as totalBs,
            CAST(ROUND(MAX(FactorHist.MaxFactor), 2) AS decimal(18, 2)) as Factor,
            CAST(ROUND((SUM(SAIPAVTA.Monto * SAFACT.Signo)/MAX(FactorHist.MaxFactor)), 2) AS decimal(18, 2)) as totalDollar"
        )
        ->joinSub($factors, 'FactorHist', function($query){
            $query->on(DB::raw("CAST(SAIPAVTA.FechaE AS date)"), '=', "FactorHist.FechaE");
        })
        ->join('SAFACT', function($query){
            $query->on("SAFACT.NumeroD", '=', "SAIPAVTA.NumeroD");
        })
        ->whereRaw("SAFACT.CodUsua " . $user_params . " AND " . $interval_query,
            $date_params)
        ->groupByRaw("SAFACT.CodUsua, CAST(SAFACT.FechaE AS date), SAIPAVTA.CodPago")
        ->orderByRaw("SAFACT.CodUsua asc, SAIPAVTA.CodPago asc")
        ->get();
    }

    public function getTotals($id){
        $query = CashRegisterData::selectRaw(
            'cash_register_data.id as id,
            cash_register_data.date as date,
            cash_register_data.cash_register_user as cash_register_user,
            workers.name as worker_name,
            cash_register_data.user_id as user_name,
            CAST(ROUND(COALESCE(dol_c_join.total, 0), 2) AS decimal(18, 2)) as total_dollar_cash,
            CAST(ROUND(COALESCE(pago_movil_bs_join.total, 0), 2) AS decimal(18, 2)) as total_pago_movil_bs,
            CAST(ROUND(COALESCE(ps_bs_join.total, 0), 2) AS decimal(18, 2)) as total_point_sale_bs,
            CAST(ROUND(COALESCE(ps_dol_join.total, 0), 2) AS decimal(18, 2)) as total_point_sale_dollar,
            CAST(ROUND(COALESCE(bs_denomination_join.total, 0), 2) AS decimal(18, 2)) as total_bs_denominations,
            CAST(ROUND(COALESCE(dollar_denomination_join.total, 0), 2) AS decimal(18, 2)) as total_dollar_denominations,
            CAST(ROUND(COALESCE(zelle_join.total, 0), 2) AS decimal(18, 2)) as total_zelle
            '
        )
        ->join('workers', 'cash_register_data.worker_id', '=', 'workers.id')
        ->leftJoin(
            DB::raw("(SELECT SUM(`dollar_cash_records`.`amount`) as `total`, `dollar_cash_records`.`cash_register_data_id` FROM `dollar_cash_records` GROUP BY `dollar_cash_records`.`cash_register_data_id`) `dol_c_join`"),
            function($join) use ($id) {
                $join->on('dol_c_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("(SELECT SUM(`pago_movil_bs_records`.`amount`) as `total`, `pago_movil_bs_records`.`cash_register_data_id` FROM `pago_movil_bs_records` GROUP BY `pago_movil_bs_records`.`cash_register_data_id`) `pago_movil_bs_join`"),
            function($join) use ($id) {
                $join->on('pago_movil_bs_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("(SELECT SUM(`point_sale_bs_records_2`.`cancel_debit` + `point_sale_bs_records_2`.`cancel_credit` 
                + `point_sale_bs_records_2`.`cancel_amex` + `point_sale_bs_records_2`.`cancel_todoticket`) as `total`, `point_sale_bs_records_2`.`cash_register_data_id` FROM `point_sale_bs_records_2` GROUP BY `point_sale_bs_records_2`.`cash_register_data_id`) `ps_bs_join`"),
            function($join) use ($id) {
                $join->on('ps_bs_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("
                (SELECT SUM(`point_sale_dollar_records`.`amount`) as `total`,
                `point_sale_dollar_records`.`cash_register_data_id`
                FROM `point_sale_dollar_records`
                GROUP BY
                    `point_sale_dollar_records`.`cash_register_data_id`
                ) `ps_dol_join`"),
            function($join) use ($id) {
                $join->on('ps_dol_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("(SELECT SUM(`bs_denomination_records`.`quantity` * `bs_denomination_records`.`denomination`) as `total`, `bs_denomination_records`.`cash_register_data_id` FROM `bs_denomination_records` GROUP BY `bs_denomination_records`.`cash_register_data_id`) `bs_denomination_join`"),
            function($join) use ($id) {
                $join->on('bs_denomination_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("(SELECT SUM(`dollar_denomination_records`.`quantity` * `dollar_denomination_records`.`denomination`) as `total`, `dollar_denomination_records`.`cash_register_data_id` FROM `dollar_denomination_records` GROUP BY `dollar_denomination_records`.`cash_register_data_id`) `dollar_denomination_join`"),
            function($join) use ($id) {
                $join->on('dollar_denomination_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->leftJoin(
            DB::raw("(SELECT SUM(`zelle_records`.`amount`) as `total`, `zelle_records`.`cash_register_data_id` FROM `zelle_records` GROUP BY `zelle_records`.`cash_register_data_id`) `zelle_join`"),
            function($join) use ($id) {
                $join->on('zelle_join.cash_register_data_id', '=', 'cash_register_data.id');
            }
        )
        ->where('cash_register_data.id', '=', $id);

        return $query->first();
    }

    public function getTotalsByInterval($start_date, $end_date){

        $date_params = [$start_date, $end_date];

        $interval_query = "cash_register_data.date BETWEEN ? AND ?";

        $query = DB
            ::table('cash_register_data')
            ->selectRaw(
                'cash_register_data.cash_register_user as cash_register_user,
                cash_register_data.date as date,
                CAST(ROUND(COALESCE(MAX(dol_c_join.total), 0), 2) AS decimal(18, 2)) as total_dollar_cash,
                CAST(ROUND(COALESCE(MAX(pago_movil_bs_join.total), 0), 2) AS decimal(18, 2)) as total_pago_movil_bs,
                CAST(ROUND(COALESCE(MAX(ps_bs_join.total), 0), 2) AS decimal(18, 2)) as total_point_sale_bs,
                CAST(ROUND(COALESCE(MAX(ps_dol_join.total), 0), 2) AS decimal(18, 2)) as total_point_sale_dollar,
                CAST(ROUND(COALESCE(MAX(bs_denomination_join.total), 0), 2) AS decimal(18, 2)) as total_bs_denominations,
                CAST(ROUND(COALESCE(MAX(dollar_denomination_join.total), 0), 2) AS decimal(18, 2)) as total_dollar_denominations,
                CAST(ROUND(COALESCE(MAX(zelle_join.total), 0), 2) AS decimal(18, 2)) as total_zelle
                '
            )
            ->join('workers', 'cash_register_data.worker_id', '=', 'workers.id')
            ->leftJoin(
                DB::raw("(SELECT SUM(`dollar_cash_records`.`amount`) as `total`, `dollar_cash_records`.`cash_register_data_id` FROM `dollar_cash_records` GROUP BY `dollar_cash_records`.`cash_register_data_id`) `dol_c_join`"),
                function($join)  {
                    $join->on('dol_c_join.cash_register_data_id', '=', 'cash_register_data.id');
                }
            )
            ->leftJoin(
                DB::raw("(SELECT SUM(`pago_movil_bs_records`.`amount`) as `total`, `pago_movil_bs_records`.`cash_register_data_id` FROM `pago_movil_bs_records` GROUP BY `pago_movil_bs_records`.`cash_register_data_id`) `pago_movil_bs_join`"),
                function($join)  {
                    $join->on('pago_movil_bs_join.cash_register_data_id', '=', 'cash_register_data.id');
                }
            )
            ->leftJoin(
                DB::raw("(SELECT SUM(`point_sale_bs_records_2`.`cancel_debit` + `point_sale_bs_records_2`.`cancel_credit` 
                    + `point_sale_bs_records_2`.`cancel_amex` + `point_sale_bs_records_2`.`cancel_todoticket`) as `total`, `point_sale_bs_records_2`.`cash_register_data_id` FROM `point_sale_bs_records_2` GROUP BY `point_sale_bs_records_2`.`cash_register_data_id`) `ps_bs_join`"),
                function($join)  {
                    $join->on('ps_bs_join.cash_register_data_id', '=', 'cash_register_data.id');
                }
            )
            ->leftJoin(
                DB::raw("
                    (SELECT SUM(`point_sale_dollar_records`.`amount`) as `total`,
                    `point_sale_dollar_records`.`cash_register_data_id`
                    FROM `point_sale_dollar_records`
                    GROUP BY
                        `point_sale_dollar_records`.`cash_register_data_id`
                    ) `ps_dol_join`"),
                function($join)  {
                    $join->on('ps_dol_join.cash_register_data_id', '=', 'cash_register_data.id');
                }
            )
            ->leftJoin(
                DB::raw("(SELECT SUM(`bs_denomination_records`.`quantity` * `bs_denomination_records`.`denomination`) as `total`, `bs_denomination_records`.`cash_register_data_id` FROM `bs_denomination_records` GROUP BY `bs_denomination_records`.`cash_register_data_id`) `bs_denomination_join`"),
                function($join)  {
                    $join->on('bs_denomination_join.cash_register_data_id', '=', 'cash_register_data.id');
                }
            )
            ->leftJoin(
                DB::raw("(SELECT SUM(`dollar_denomination_records`.`quantity` * `dollar_denomination_records`.`denomination`) as `total`, `dollar_denomination_records`.`cash_register_data_id` FROM `dollar_denomination_records` GROUP BY `dollar_denomination_records`.`cash_register_data_id`) `dollar_denomination_join`"),
                function($join)  {
                    $join->on('dollar_denomination_join.cash_register_data_id', '=', 'cash_register_data.id');
                }
            )
            ->leftJoin(
                DB::raw("(SELECT SUM(`zelle_records`.`amount`) as `total`, `zelle_records`.`cash_register_data_id` FROM `zelle_records` GROUP BY `zelle_records`.`cash_register_data_id`) `zelle_join`"),
                function($join)  {
                    $join->on('zelle_join.cash_register_data_id', '=', 'cash_register_data.id');
                }
            )
            ->whereRaw($interval_query, $date_params)
            ->groupByRaw("cash_register_user, date")
            ->orderByRaw("cash_register_user asc, date asc");

        return $query->get();
    }

    public function getZelleRecords($start_date, $end_date){
        $date_params = [$start_date, $end_date];

        $interval_query = "cash_register_data.date BETWEEN ? AND ?";

        return DB
            ::table('cash_register_data')
            ->selectRaw('
                cash_register_data.date as date,
                cash_register_data.cash_register_user as cash_register_user,
                CAST(ROUND(zelle_records.amount, 2) AS decimal(10, 2)) as amount
            ')
            ->join("zelle_records", function($join) {
                $join->on('zelle_records.cash_register_data_id', '=', 'cash_register_data.id');
            })
            ->whereRaw($interval_query, $date_params)
            ->orderByRaw("cash_register_data.cash_register_user asc, cash_register_data.date asc, amount asc")
            ->get();
    }

    public function getFactorByDate($start_date, $end_date){
        /* Consulta para obtener el valor del factor por cada dia */
        $date_params = ($start_date === $end_date) ? [$start_date] : [$start_date, $end_date];

        $interval_query = ($start_date === $end_date)
            ? "CAST(SAFACT.FechaE as date) = ?"
            : "CAST(SAFACT.FechaE as date) BETWEEN CAST(? as date) AND CAST(? as date)";

        return DB::connection('saint_db')
            ->table('SAFACT')
            ->selectRaw("ROUND(MAX(COALESCE(SAFACT.Factor, 0)), 2) as MaxFactor, CAST(SAFACT.FechaE as date) as FechaE")
            ->whereRaw($interval_query, $date_params)
            ->groupByRaw("CAST(SAFACT.FechaE as date)")
            ->orderByRaw("CAST(SAFACT.FechaE as date)")
            ->get();
    }
}
