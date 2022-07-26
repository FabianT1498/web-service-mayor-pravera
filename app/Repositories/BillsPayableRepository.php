<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BillsPayableRepository implements BillsPayableRepositoryInterface
{

    // Metodo para obtener las facturas por pagar
    public function getBillsPayableFromSaint($is_dolar, $before_emission_date, $bill_type, $nro_doc, $cod_prov){

        $is_bill_NE = config('constants.BILL_PAYABLE_TYPE.' . $bill_type) === config('constants.BILL_PAYABLE_TYPE.NE');

        $sacomp_sub_join =  $is_bill_NE
            ?
                "(SELECT SACOMP.NumeroD, SACOMP.CodProv, SACOMP.FACTORP FROM SACOMP
                LEFT JOIN (SELECT NumeroD, CodProv from SACOMP WHERE SACOMP.TipoCom = 'H' AND CAST(SACOMP.FechaE AS date)  <= '". $before_emission_date . "') SACOMP_FAC
                    ON (SACOMP.NumeroD = SACOMP_FAC.NumeroD AND SACOMP.CodProv = SACOMP_FAC.CodProv)
                WHERE SACOMP.TipoCom = 'J' AND CAST(SACOMP.FechaE AS date)  <= '" . $before_emission_date . "' AND SACOMP_FAC.NumeroD IS NULL AND SACOMP_FAC.CodProv IS NULL) SACOMP_SUB"
            :
                "(SELECT NumeroD, CodProv, SACOMP.FACTORP from SACOMP WHERE SACOMP.TipoCom = 'H') SACOMP_SUB";

        return DB
            ::connection('saint_db')
            ->table('SAACXP')
            ->selectRaw("SAACXP.NumeroD, SAACXP.CodProv, SAACXP.Descrip, SAACXP.TipoCxP, CAST(ROUND(SAACXP.Monto, 2) AS decimal(10, 2)) AS MontoTotal,
                CAST(ROUND(SAACXP.Saldo, 2) AS decimal(10, 2)) AS MontoPagar, SACOMP_02.USD AS esDolar, " .
                    ($is_bill_NE ? "CAST(COALESCE(SACOMP_02.Tasa, 0) AS decimal(10, 2))" : "CAST(COALESCE(SACOMP_SUB.FACTORP, 0) AS decimal(10, 2))") . " AS Tasa, CAST(SAACXP.FechaI AS date) as FechaPosteo, CAST(SAACXP.FechaE AS date) AS FechaE")
            ->join(DB::raw($sacomp_sub_join),
                function($join){
                    $join->on('SACOMP_SUB.NumeroD', '=', 'SAACXP.NumeroD')
                        ->on('SACOMP_SUB.CodProv', '=', 'SAACXP.CodProv');
                }
            )
            ->join('SACOMP_02', function($join){
                $join->on('SACOMP_SUB.NumeroD', '=', 'SACOMP_02.NumeroD')
                    ->on('SACOMP_SUB.CodProv', '=', 'SACOMP_02.CodProv');
                }
            )
            ->whereRaw("SAACXP.TipoCxP = 10 AND SAACXP.Saldo > 0 AND CAST(SAACXP.FechaE AS date) <= '" . $before_emission_date . "' AND SACOMP_02.USD = " . $is_dolar 
                . ($nro_doc && $nro_doc !== '' ? " AND UPPER(SAACXP.NumeroD) = UPPER('" . $nro_doc . "')" : '') . ( $cod_prov && $cod_prov !== '' ? " AND SAACXP.CodProv = '" . $cod_prov . "'" : ''))
            ->orderByRaw("SAACXP.FechaE DESC");
    }

    public function getBillPayableFromSaint($cod_prov, $n_doc, $bill_type){
        $is_bill_NE = config('constants.BILL_PAYABLE_TYPE.' . $bill_type) === config('constants.BILL_PAYABLE_TYPE.NE');

        $sacomp_sub_join =  $is_bill_NE
            ?
                "(SELECT SACOMP.NumeroD, SACOMP.CodProv, SACOMP.FACTORP FROM SACOMP
                LEFT JOIN (SELECT NumeroD, CodProv from SACOMP WHERE SACOMP.TipoCom = 'H') SACOMP_FAC
                    ON (SACOMP.NumeroD = SACOMP_FAC.NumeroD AND SACOMP.CodProv = SACOMP_FAC.CodProv)
                WHERE SACOMP.TipoCom = 'J' AND SACOMP_FAC.NumeroD IS NULL AND SACOMP_FAC.CodProv IS NULL) SACOMP_SUB"
            :
                "(SELECT NumeroD, CodProv, SACOMP.FACTORP from SACOMP WHERE SACOMP.TipoCom = 'H') SACOMP_SUB";

        return DB
            ::connection('saint_db')
            ->table('SAACXP')
            ->selectRaw("SAACXP.NumeroD, SAACXP.CodProv, SAACXP.Descrip, SAACXP.TipoCxP, CAST(ROUND(SAACXP.Monto, 2) AS decimal(10, 2)) AS MontoTotal,
                CAST(ROUND(SAACXP.Saldo, 2) AS decimal(10, 2)) AS MontoPagar, SACOMP_02.USD AS e," .
                    ($is_bill_NE ? "COALESCE(SACOMP_02.Tasa, 0)" : "COALESCE(SACOMP_SUB.FACTORP, 0)") . " AS Tasa, CAST(SAACXP.FechaI AS date) as FechaPosteo, CAST(SAACXP.FechaE AS date) AS FechaE")
            ->join(DB::raw($sacomp_sub_join),
                function($join){
                    $join->on('SACOMP_SUB.NumeroD', '=', 'SAACXP.NumeroD')
                        ->on('SACOMP_SUB.CodProv', '=', 'SAACXP.CodProv');
                }
            )
            ->join('SACOMP_02', function($join){
                $join->on('SACOMP_SUB.NumeroD', '=', 'SACOMP_02.NumeroD')
                    ->on('SACOMP_SUB.CodProv', '=', 'SACOMP_02.CodProv');
                }
            )
            ->whereRaw("SAACXP.CodProv = ? AND SAACXP.NumeroD = ?", [$cod_prov, $n_doc])
            ->first();
    }

    public function getBillPayable($n_doc, $cod_prov){

        return DB
            ::connection('web_services_db')
            ->table('bills_payable')
            ->selectRaw("bills_payable.nro_doc as NumeroD, bills_payable.cod_prov as CodProv, bills_payable.bill_type as TipoCom, CAST(ROUND(bills_payable.amount, 2) AS decimal(28, 2)) as MontoTotal,
                bills_payable.is_dollar as esDolar, bills_payable.status as Estatus, bills_payable.cod_prov as CodProv, bills_payable.descrip_prov as DescripProv, bills_payable.tasa as Tasa, 
                bills_payable.bill_payable_schedules_id as ScheduleID, bills_payable.bill_payable_groups_id as GroupID, bill_payable_schedules.start_date as ScheduleStartDate,
                bill_payable_schedules.end_date as ScheduleEndDate,
                CASE WHEN bills_payable.is_dollar = 1 
                    THEN CAST(ROUND(bills_payable.amount - (COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0)), 2) AS decimal(28, 2))
                ELSE CAST(ROUND((bills_payable.amount / COALESCE(bills_payable.tasa, 1)) - (COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0)), 2) AS decimal(28,2))
                END AS MontoPagar, CAST(ROUND(COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0), 2) AS decimal(28, 2)) AS MontoPagado")
            ->leftJoin("bill_payable_schedules", "bill_payable_schedules.id", "=", "bills_payable.bill_payable_schedules_id")
            ->leftJoin(DB::raw("(SELECT bills_payable_payments.nro_doc, bills_payable_payments.cod_prov, SUM(bill_payments.amount / bill_payments_bs.tasa) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_bs ON bill_payments_bs.bill_payments_id = bill_payments.id
                    WHERE bills_payable_payments.nro_doc = '" . $n_doc . "' AND bills_payable_payments.cod_prov = '" . $cod_prov . 
                    "' GROUP BY bills_payable_payments.nro_doc, bills_payable_payments.cod_prov) AS bill_payments_bs_div"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_bs_div.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_bs_div.cod_prov');
                })
            ->leftJoin(DB::raw("(SELECT bills_payable_payments.nro_doc, bills_payable_payments.cod_prov, SUM(bill_payments.amount) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_dollar ON bill_payments_dollar.bill_payments_id = bill_payments.id
                    WHERE bills_payable_payments.nro_doc = '" . $n_doc . "' AND bills_payable_payments.cod_prov = '" . $cod_prov .
                    "' GROUP BY bills_payable_payments.nro_doc, bills_payable_payments.cod_prov) AS bill_payments_dollar"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_dollar.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_dollar.cod_prov');
                })
            
            // ->leftJoin(DB::raw("(SELECT bill_payments.nro_doc, bill_payments.cod_prov, SUM(bill_payments.amount / bill_payments_bs.tasa) as total_paid FROM bill_payments 
            //         INNER JOIN bill_payments_bs ON bill_payments_bs.bill_payments_id = bill_payments.id
            //         WHERE bill_payments.nro_doc = '" . $n_doc . "' AND bill_payments.cod_prov = '" . $cod_prov .  
            //         "' GROUP BY bill_payments.cod_prov, bill_payments.nro_doc) AS bill_payments_bs_div"),
            //     function($join){
            //         $join->on('bills_payable.nro_doc', '=', 'bill_payments_bs_div.nro_doc')
            //             ->on('bills_payable.cod_prov', '=', 'bill_payments_bs_div.cod_prov');
            //     })
            // ->leftJoin(DB::raw("(SELECT bill_payments.nro_doc, bill_payments.cod_prov, SUM(bill_payments.amount) as total_paid FROM bill_payments 
            //         INNER JOIN bill_payments_dollar ON bill_payments_dollar.bill_payments_id = bill_payments.id
            //         WHERE bill_payments.nro_doc = '" . $n_doc . "' AND bill_payments.cod_prov = '" . $cod_prov .  
            //         "' GROUP BY bill_payments.cod_prov, bill_payments.nro_doc) AS bill_payments_dollar"),
            //     function($join){
            //         $join->on('bills_payable.nro_doc', '=', 'bill_payments_dollar.nro_doc')
            //             ->on('bills_payable.cod_prov', '=', 'bill_payments_dollar.cod_prov');
            //     })
            ->whereRaw("bills_payable.nro_doc = ? AND bills_payable.cod_prov = ?", [$n_doc, $cod_prov])
            ->first();
    }

    /** BILL PAYABLE GROUPS QUERIES */
    public function getBillPayableGroups($cod_prov = null){
        $query = DB
            ::connection('web_services_db')
            ->table('bill_payable_groups')
            ->selectRaw("bill_payable_groups.id as ID, MAX(bills_payable.cod_prov) as CodProv,
                MAX(bills_payable.bill_payable_schedules_id) AS ScheduleID, MAX(bill_payable_groups.descrip_prov) as DescripProv,
                MAX(bill_payable_groups.status) as Estatus, CAST(ROUND(SUM(COALESCE(bills_payable.amount, 0)), 2) AS decimal(28, 2)) AS MontoTotal,
                CAST(ROUND(COALESCE(MAX(bill_payments_bs_div.total_paid), 0) + COALESCE(MAX(bill_payments_dollar.total_paid), 0), 2) AS decimal(28, 2)) AS MontoPagado")
            ->leftJoin(DB::raw("(SELECT bills_payable.descrip_prov, bills_payable.bill_payable_schedules_id, bills_payable.bill_payable_groups_id, bills_payable.nro_doc, bills_payable.cod_prov,
                    CASE WHEN bills_payable.is_dollar = 1 
                        THEN bills_payable.amount
                        ELSE bills_payable.amount / COALESCE(bills_payable.tasa, 1)
                        END AS amount FROM bills_payable) AS bills_payable"), function($join){
                    $join->on('bills_payable.bill_payable_groups_id', '=', 'bill_payable_groups.id');
            })
            ->leftJoin(DB::raw("(SELECT MAX(bills_payable_payments.nro_doc) AS nro_doc, MAX(bills_payable_payments.cod_prov) as cod_prov, SUM(bill_payments.amount / bill_payments_bs.tasa) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_bs ON bill_payments_bs.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.bill_payments_id, bills_payable_payments.cod_prov, bills_payable_payments.nro_doc) AS bill_payments_bs_div"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_bs_div.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_bs_div.cod_prov');
                })
            ->leftJoin(DB::raw("(SELECT MAX(bills_payable_payments.nro_doc) AS nro_doc, MAX(bills_payable_payments.cod_prov) AS cod_prov, SUM(bill_payments.amount) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_dollar ON bill_payments_dollar.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.bill_payments_id, bills_payable_payments.cod_prov, bills_payable_payments.nro_doc) AS bill_payments_dollar"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_dollar.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_dollar.cod_prov');
                });
            
        $where = '';

        if (!is_null($cod_prov)){
            $where = "bill_payable_groups.cod_prov = '" . $cod_prov . "'";
        }

        if ($where !== ''){
            $query = $query->whereRaw($where);
        }

        $query = $query->groupByRaw("bill_payable_groups.id");
        return $query;
    }

    public function getBillPayableGroupsByScheduleID($schedule_id){

        return $this->getBillPayableGroups(null)
            ->havingRaw("MAX(bills_payable.bill_payable_schedules_id) = '" . $schedule_id . "'")
            ->get();
    }

    public function getLastBillPayableTasaByGroupID($group_id = null){
        $query = DB
            ::connection('web_services_db')
            ->table('bill_payable_groups')
            ->selectRaw("bills_payable.tasa as Tasa")
            ->leftJoin("bills_payable", "bills_payable.bill_payable_groups_id", "=", "bill_payable_groups.id");
            
        $where = '';

        if (!is_null($group_id)){
            $where = "bill_payable_groups.id = '" . $group_id . "'";
        }
   
        $query = $query->whereRaw($where)->orderByRaw("bills_payable.emission_date DESC");

        return $query->first();
    }

    public function getBillPayableGroupByID($group_id = null){
        $query = DB
            ::connection('web_services_db')
            ->table('bill_payable_groups')
            ->selectRaw("bill_payable_groups.id as ID, MAX(bill_payable_groups.descrip_prov) as DescripProv,
                MAX(bills_payable.bill_payable_schedules_id) as ScheduleID, MAX(bill_payable_schedules.start_date) as ScheduleStartDate,
                MAX(bill_payable_schedules.end_date) as ScheduleEndDate,
                MAX(bill_payable_groups.status) as Estatus, CAST(ROUND(SUM(COALESCE(bills_payable.amount, 0)), 2) AS decimal(28, 2)) AS MontoTotal,
                CAST(ROUND(COALESCE(MAX(bill_payments_bs_div.total_paid), 0) + COALESCE(MAX(bill_payments_dollar.total_paid), 0), 2) AS decimal(28, 2)) AS MontoPagado,
                CAST(ROUND(SUM(COALESCE(bills_payable.amount, 0)) - (COALESCE(MAX(bill_payments_bs_div.total_paid), 0) + COALESCE(MAX(bill_payments_dollar.total_paid), 0)), 2) AS decimal(28, 2)) AS MontoPagar")
            ->leftJoin(DB::raw("(SELECT bills_payable.bill_payable_groups_id, bills_payable.nro_doc, bills_payable.cod_prov,
                    CASE WHEN bills_payable.is_dollar = 1 
                        THEN bills_payable.amount
                        ELSE bills_payable.amount / COALESCE(bills_payable.tasa, 1)
                        END AS amount, bills_payable.bill_payable_schedules_id FROM bills_payable) AS bills_payable"), function($join){
                    $join->on('bills_payable.bill_payable_groups_id', '=', 'bill_payable_groups.id');
            })
            ->leftJoin("bill_payable_schedules", "bill_payable_schedules.id", "=", "bills_payable.bill_payable_schedules_id")
            ->leftJoin(DB::raw("(SELECT MAX(bills_payable_payments.nro_doc) AS nro_doc, MAX(bills_payable_payments.cod_prov) as cod_prov, SUM(bill_payments.amount / bill_payments_bs.tasa) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_bs ON bill_payments_bs.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.bill_payments_id, bills_payable_payments.cod_prov, bills_payable_payments.nro_doc) AS bill_payments_bs_div"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_bs_div.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_bs_div.cod_prov');
                })
            ->leftJoin(DB::raw("(SELECT MAX(bills_payable_payments.nro_doc) AS nro_doc, MAX(bills_payable_payments.cod_prov) AS cod_prov, SUM(bill_payments.amount) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_dollar ON bill_payments_dollar.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.bill_payments_id, bills_payable_payments.cod_prov, bills_payable_payments.nro_doc) AS bill_payments_dollar"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_dollar.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_dollar.cod_prov');
                });
            
        $where = '';

        if (!is_null($group_id)){
            $where = "bill_payable_groups.id = '" . $group_id . "'";
        }
   
        $query = $query->whereRaw($where)->groupByRaw("bill_payable_groups.id");

        return $query->first();
    }

    public function getBillPayablePaymentsByGroupID($group_id, $is_dollar_payment = 0){
        $query = DB
            ::connection('web_services_db')
            ->table('bill_payable_groups')
            ->selectRaw("bill_payable_groups.id as ID, bill_payable_groups.descrip_prov as DescripProv,
                CAST(ROUND(bill_payments.amount, 2) AS decimal(28, 2)) AS Amount, bill_payments.date as Date, " 
                . ($is_dollar_payment ? "bill_payments.payment_method as PaymentMethod,
                bill_payments.retirement_date as RetirementDate" : "bill_payments.ref_number as RefNumber,
                bill_payments.tasa as Tasa, bill_payments.bank_name as BankName, bill_payments.dollar_amount as DollarAmount"))
            ->join(DB::raw("(SELECT bills_payable.bill_payable_groups_id, MAX(bills_payable.nro_doc) AS nro_doc, MAX(bills_payable.cod_prov) AS cod_prov FROM bills_payable 
                    WHERE bills_payable.bill_payable_groups_id = " . $group_id . " GROUP BY bills_payable.bill_payable_groups_id) AS bills_payable"), function($join){
                    $join->on('bills_payable.bill_payable_groups_id', '=', 'bill_payable_groups.id');
            });

            if ($is_dollar_payment){
                $query = $query->join(DB::raw("(SELECT bills_payable_payments.nro_doc AS nro_doc, bills_payable_payments.cod_prov AS cod_prov, bill_payments.amount as amount,
                bill_payments.date as date, bill_payments_dollar.payment_method as payment_method,
                bill_payments_dollar.retirement_date as retirement_date FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_dollar ON bill_payments_dollar.bill_payments_id = bill_payments.id) AS bill_payments"),
                    function($join){
                        $join->on('bills_payable.nro_doc', '=', 'bill_payments.nro_doc')
                            ->on('bills_payable.cod_prov', '=', 'bill_payments.cod_prov');
                    });
            } else {
                $query = $query->join(DB::raw("(SELECT bills_payable_payments.nro_doc AS nro_doc, bills_payable_payments.cod_prov as cod_prov,
                    bill_payments.amount as amount, (bill_payments.amount / bill_payments_bs.tasa) as dollar_amount, bill_payments_bs.bank_name as bank_name,
                    bill_payments_bs.ref_number, bill_payments_bs.tasa, bill_payments.date as date FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_bs ON bill_payments_bs.bill_payments_id = bill_payments.id) AS bill_payments"),
                    function($join){
                        $join->on('bills_payable.nro_doc', '=', 'bill_payments.nro_doc')
                            ->on('bills_payable.cod_prov', '=', 'bill_payments.cod_prov');
                    });
            }
        
        $where = '';

        if (!is_null($group_id)){
            $where = "bill_payable_groups.id = '" . $group_id . "'";
        }
   
        $query = $query->whereRaw($where);

        return $query->get();
    }

    public function getBillPayablePaymentsCountByGroupID($group_id){
        $query = DB
            ::connection('web_services_db')
            ->table('bill_payable_groups')
            ->selectRaw("COUNT(bills_payable_payments.bill_payments_id) as count")
            ->join(DB::raw("(SELECT bills_payable.bill_payable_groups_id, MAX(bills_payable.nro_doc) AS nro_doc, MAX(bills_payable.cod_prov) AS cod_prov FROM bills_payable 
                    WHERE bills_payable.bill_payable_groups_id = " . $group_id . " GROUP BY bills_payable.bill_payable_groups_id) AS bills_payable"), function($join){
                    $join->on('bills_payable.bill_payable_groups_id', '=', 'bill_payable_groups.id');
            })
            ->join("bills_payable_payments", function($join){
                $join->on('bills_payable.nro_doc', '=', 'bills_payable_payments.nro_doc')
                    ->on('bills_payable.cod_prov', '=', 'bills_payable_payments.cod_prov');
            });

            
        $where = '';

        if (!is_null($group_id)){
            $where = "bill_payable_groups.id = '" . $group_id . "'";
        }
   
        $query = $query->whereRaw($where);

        return $query->first();
    }

    public function getBillsPayableByGroupID($group_id, $is_dollar = 0){
        $where_raw = '';

        $query = DB
            ::connection('web_services_db')
            ->table('bills_payable')
            ->selectRaw("bills_payable.nro_doc as NumeroD, bills_payable.cod_prov as CodProv, bills_payable.emission_date as FechaE, bills_payable.bill_type as TipoCom,
                CAST(ROUND(bills_payable.amount, 2) AS decimal(28, 2)) as MontoTotal, bills_payable.is_dollar as esDolar, bills_payable.status as Status, bills_payable.tasa as Tasa,
                CASE WHEN bills_payable.is_dollar = 1 
                    THEN CAST(ROUND(bills_payable.amount - (COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0)), 2) AS decimal(28, 2))
                ELSE CAST(ROUND((bills_payable.amount / COALESCE(bills_payable.tasa, 1)) - (COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0)), 2) AS decimal(28, 2))
                END AS MontoPagar,
                CAST(ROUND(COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0), 2) AS decimal(28, 2)) AS MontoPagado,
                bills_payable.bill_payable_schedules_id as BillPayableSchedulesID, bills_payable.bill_payable_groups_id as BillPayableGroupsID, bills_payable.descrip_prov as Descrip")
            ->leftJoin(DB::raw("(SELECT bills_payable_payments.nro_doc, bills_payable_payments.cod_prov, SUM(bill_payments.amount / bill_payments_bs.tasa) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_bs ON bill_payments_bs.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.nro_doc, bills_payable_payments.cod_prov) AS bill_payments_bs_div"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_bs_div.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_bs_div.cod_prov');
                })
            ->leftJoin(DB::raw("(SELECT bills_payable_payments.nro_doc, bills_payable_payments.cod_prov, SUM(bill_payments.amount) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_dollar ON bill_payments_dollar.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.nro_doc, bills_payable_payments.cod_prov) AS bill_payments_dollar"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_dollar.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_dollar.cod_prov');
                });
           
        $where_raw = "bills_payable.bill_payable_groups_id = " . $group_id;

        
        $where_raw = $where_raw . " AND bills_payable.is_dollar = " . $is_dollar;
        
        $query = $query->whereRaw($where_raw);

        return $query->get();
    }
    
    /** BILL PAYABLE QUERIES */
    public function getBillPayablePaymentsCount($n_doc, $cod_prov){

        return DB
            ::connection('web_services_db')
            ->table('bills_payable_payments')
            ->selectRaw("COALESCE(COUNT(bill_payments.id), 0) as count")
            ->join('bill_payments', function($join){
                $join->on('bill_payments.id', '=', 'bills_payable_payments.bill_payments_id');
            })
            ->whereRaw("bills_payable_payments.nro_doc = ? AND bills_payable_payments.cod_prov = ?", [$n_doc, $cod_prov])
            ->groupByRaw("bills_payable_payments.cod_prov, bills_payable_payments.nro_doc")
            ->first();
    }

    public function getBillPayablePaymentsBs($n_doc, $cod_prov){

        return DB
            ::connection('web_services_db')
            ->table('bills_payable_payments')
            ->selectRaw("bill_payments.id as id, bills_payable_payments.nro_doc as NumeroD, bills_payable_payments.cod_prov as CodProv, bill_payments.amount as Amount,
                bill_payments.date as Date, bill_payments.is_dollar as esDolar, bill_payments_bs.ref_number as RefNumber,
                bill_payments_bs.tasa as Tasa, bill_payments_bs.bank_name as BankName")
            ->join('bill_payments', function($join){
                $join->on('bill_payments.id', '=', 'bills_payable_payments.bill_payments_id');
            })
            ->join('bill_payments_bs', function($join){
                $join->on('bill_payments.id', '=', 'bill_payments_bs.bill_payments_id');
            })
            ->whereRaw("bills_payable_payments.nro_doc = ? AND bills_payable_payments.cod_prov = ? AND bill_payments.is_dollar = 0", [$n_doc, $cod_prov]);
    }

    public function getBillPayablePaymentsDollar($n_doc, $cod_prov){

        return DB
            ::connection('web_services_db')
            ->table('bills_payable_payments')
            ->selectRaw("bill_payments.id as id, bills_payable_payments.nro_doc as NumeroD, bills_payable_payments.cod_prov as CodProv, bill_payments.amount as Amount,
                bill_payments.date as Date, bill_payments.is_dollar as esDolar, bill_payments_dollar.payment_method as PaymentMethod,
                bill_payments_dollar.retirement_date as RetirementDate")
            ->join('bill_payments', function($join){
                $join->on('bill_payments.id', '=', 'bills_payable_payments.bill_payments_id');
            })
            ->join('bill_payments_dollar', function($join){
                $join->on('bill_payments.id', '=', 'bill_payments_dollar.bill_payments_id');
            })
            ->whereRaw("bills_payable_payments.nro_doc = ? AND bills_payable_payments.cod_prov = ? AND bill_payments.is_dollar = 1", [$n_doc, $cod_prov]);
    }

    public function getBillsPayable($is_dolar, $before_emission_date, $bill_type, $nro_doc, $cod_prov, $is_scheduled_bill = 0, $is_group_bill = 0){
        
        if (is_null($nro_doc)){
            $nro_doc = '';
        }

        $query = DB
            ::connection('web_services_db')
            ->table('bills_payable')
            ->selectRaw("bills_payable.nro_doc as NumeroD, bills_payable.cod_prov as CodProv, bills_payable.emission_date as FechaE,  bills_payable.bill_type as TipoCom, bills_payable.amount as MontoTotal,
                bills_payable.is_dollar as esDolar, bills_payable.status as Status, bills_payable.tasa as Tasa,
                CASE WHEN bills_payable.is_dollar = 1 
                    THEN CAST(ROUND(bills_payable.amount - (COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0)), 2) AS decimal(28, 2))
                ELSE CAST(ROUND((bills_payable.amount / COALESCE(bills_payable.tasa, 1)) - (COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0)), 2) AS decimal(28, 2))
                END AS MontoPagar,
                CAST(ROUND(COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0), 2) AS decimal(28, 2)) AS MontoPagado,
                bills_payable.bill_payable_schedules_id as BillPayableSchedulesID, bills_payable.descrip_prov as Descrip,
                bills_payable.bill_payable_groups_id AS BillPayableGroupsID")
            ->leftJoin(DB::raw("(SELECT bills_payable_payments.nro_doc, bills_payable_payments.cod_prov, SUM(bill_payments.amount / bill_payments_bs.tasa) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_bs ON bill_payments_bs.bill_payments_id = bill_payments.id 
                    GROUP BY bills_payable_payments.nro_doc, bills_payable_payments.cod_prov) AS bill_payments_bs_div "),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_bs_div.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_bs_div.cod_prov');
                })
            ->leftJoin(DB::raw("(SELECT bills_payable_payments.nro_doc, bills_payable_payments.cod_prov, SUM(bill_payments.amount) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_dollar ON bill_payments_dollar.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.nro_doc, bills_payable_payments.cod_prov) AS bill_payments_dollar"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_dollar.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_dollar.cod_prov');
                })
            ->whereRaw("bills_payable.emission_date <= '" . $before_emission_date . "' AND bills_payable.bill_payable_schedules_id " . ($is_scheduled_bill ? "IS NOT" : "IS" ) . " NULL AND bills_payable.is_dollar = " . $is_dolar .
                " AND bills_payable.bill_type = '" . $bill_type . "' AND bills_payable.bill_payable_groups_id " . ($is_group_bill ? "IS NOT" : "IS") . " NULL "  
                . ($nro_doc && $nro_doc !== '' ? " AND UPPER(bills_payable.nro_doc) = UPPER('" . $nro_doc . "')" : '') . ( $cod_prov && $cod_prov !== '' ? " AND bills_payable.cod_prov = '" . $cod_prov . "'" : ''));

        return $query;
    }

    public function getBillsPayableByScheduleId($bill_payable_schedules_id, $is_dollar = 0, $is_grouped = false){
        
        $where_raw = '';

        $query = DB
            ::connection('web_services_db')
            ->table('bills_payable')
            ->selectRaw("bills_payable.nro_doc as NumeroD, bills_payable.cod_prov as CodProv, bills_payable.emission_date as FechaE, bills_payable.bill_type as TipoCom,
                bills_payable.amount as MontoTotal, bills_payable.is_dollar as esDolar, bills_payable.status as Status, bills_payable.tasa as Tasa,
                CASE WHEN bills_payable.is_dollar = 1 
                    THEN CAST(ROUND(bills_payable.amount - (COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0)), 2) AS decimal(28, 2))
                ELSE CAST(ROUND((bills_payable.amount / COALESCE(bills_payable.tasa, 1)) - (COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0)), 2) AS decimal(28, 2))
                END AS MontoPagar,
                CAST(ROUND(COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0), 2) AS decimal(28, 2)) AS MontoPagado,
                bills_payable.bill_payable_schedules_id as BillPayableSchedulesID, bills_payable.bill_payable_groups_id as BillPayableGroupsID, bills_payable.descrip_prov as Descrip")
            ->leftJoin(DB::raw("(SELECT bills_payable_payments.nro_doc, bills_payable_payments.cod_prov, SUM(bill_payments.amount / bill_payments_bs.tasa) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_bs ON bill_payments_bs.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.nro_doc, bills_payable_payments.cod_prov) AS bill_payments_bs_div"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_bs_div.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_bs_div.cod_prov');
                })
            ->leftJoin(DB::raw("(SELECT bills_payable_payments.nro_doc, bills_payable_payments.cod_prov, SUM(bill_payments.amount) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_dollar ON bill_payments_dollar.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.nro_doc, bills_payable_payments.cod_prov) AS bill_payments_dollar"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_dollar.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_dollar.cod_prov');
                });
           
        $where_raw = "bills_payable.bill_payable_schedules_id = " . $bill_payable_schedules_id;

        
        $where_raw = $where_raw . " AND bills_payable.is_dollar = " . $is_dollar;

        $where_raw = $where_raw . " AND bills_payable.bill_payable_groups_id IS " . ($is_grouped ? "NOT" : "") . " NULL";
        
        $query = $query->whereRaw($where_raw);

        return $query;
    }

    public function getBillsPayableByIds($ids = ''){
     
        $query = DB
            ::connection('web_services_db')
            ->table('bills_payable')
            ->selectRaw("bills_payable.nro_doc as NumeroD, bills_payable.cod_prov as CodProv, bills_payable.bill_type as TipoCom, bills_payable.amount as MontoTotal,
                CASE WHEN bills_payable.is_dollar = 1 
                    THEN CAST(ROUND(bills_payable.amount - (COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0)), 2) AS decimal(28, 2))
                    ELSE CAST(ROUND((bills_payable.amount / COALESCE(bills_payable.tasa, 1)) - (COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0)), 2) AS decimal(28, 2))
                END AS MontoPagar, bills_payable.is_dollar as esDolar, bills_payable.tasa as Tasa,  bills_payable.status as Estatus,
                CAST(ROUND(COALESCE(bill_payments_bs_div.total_paid, 0) + COALESCE(bill_payments_dollar.total_paid, 0), 2) AS decimal(28, 2)) AS MontoPagado,
                bills_payable.bill_payable_schedules_id as BillPayableSchedulesID, bills_payable.bill_payable_groups_id AS BillPayableGroupsID")
            ->leftJoin(DB::raw("(SELECT bills_payable_payments.nro_doc, bills_payable_payments.cod_prov, SUM(bill_payments.amount / bill_payments_bs.tasa) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_bs ON bill_payments_bs.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.nro_doc, bills_payable_payments.cod_prov) AS bill_payments_bs_div
                    "),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_bs_div.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_bs_div.cod_prov');
                })
            ->leftJoin(DB::raw("(SELECT bills_payable_payments.nro_doc, bills_payable_payments.cod_prov, SUM(bill_payments.amount) as total_paid FROM bills_payable_payments 
                    INNER JOIN bill_payments ON bills_payable_payments.bill_payments_id = bill_payments.id
                    INNER JOIN bill_payments_dollar ON bill_payments_dollar.bill_payments_id = bill_payments.id
                    GROUP BY bills_payable_payments.nro_doc, bills_payable_payments.cod_prov) AS bill_payments_dollar"),
                function($join){
                    $join->on('bills_payable.nro_doc', '=', 'bill_payments_dollar.nro_doc')
                        ->on('bills_payable.cod_prov', '=', 'bill_payments_dollar.cod_prov');
                })
            ->whereRaw($ids);
            
        return $query;
    } 
}
