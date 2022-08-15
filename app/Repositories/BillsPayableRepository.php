<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class BillsPayableRepository implements BillsPayableRepositoryInterface
{

    // Metodo para obtener las facturas por pagar
    public function getBillsPayableFromSaint($is_dolar, $before_emission_date, $bill_type){

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
            ->whereRaw("SAACXP.TipoCxP = 10 AND SAACXP.Saldo > 0 AND CAST(SAACXP.FechaE AS date) <= '" . $before_emission_date . "' AND SACOMP_02.USD = " . $is_dolar)
            ->orderByRaw("SAACXP.FechaE DESC");
    }

    public function getBillPayable($n_doc, $cod_prov){

        return DB
            ::connection('web_services_db')
            ->table('bills_payable')
            ->selectRaw("bills_payable.nro_doc as NumeroD, bills_payable.cod_prov as CodProv, bills_payable.bill_type as TipoCom, bills_payable.amount as MontoPagar,
                bills_payable.is_dollar as esDolar, bills_payable.status as Status, bills_payable.cod_prov as CodProv, 
                bills_payable.bill_payable_schedules_id as ScheduleID, bill_payable_schedules.start_date as ScheduleStartDate, bill_payable_schedules.end_date as ScheduleEndDate")
            ->leftjoin("bill_payable_schedules", "bill_payable_schedules.id", "=", "bills_payable.bill_payable_schedules_id")
            ->whereRaw("nro_doc = ? AND cod_prov = ?", [$n_doc, $cod_prov])
            ->first();
    }

    public function getBillPayablePayments($n_doc, $cod_prov){

        return DB
            ::connection('web_services_db')
            ->table('bill_payments')
            ->selectRaw("bill_payments.nro_doc as NumeroD, bill_payments.cod_prov as CodProv, bill_payments.amount as Amount, bill_payments.ref_number as RefNumber,
                bill_payments.is_dollar as esDolar, bill_payments.tasa as Tasa, bill_payments.bank_name as BankName, 
                bill_payments.date as Date")
            ->whereRaw("nro_doc = ? AND cod_prov = ?", [$n_doc, $cod_prov]);
    }

    public function getBillsPayable($ids = ''){
        $whereRaw = '';

        if ($ids !== ''){
            $whereRaw = $whereRaw .  $ids;
        }

        $query = DB
            ::connection('web_services_db')
            ->table('bills_payable')
            ->selectRaw("bills_payable.nro_doc as NumeroD, bills_payable.cod_prov as CodProv, bills_payable.bill_type as TipoCom, bills_payable.amount as MontoTotal,
                bills_payable.is_dollar as esDolar, bills_payable.status as Status, bills_payable.tasa as Tasa, (bills_payable.amount - COALESCE(bill_payments.total_paid, 0)) as MontoPagar")
            ->leftJoin(DB::raw('(SELECT bill_payments.nro_doc, bill_payments.cod_prov, SUM(bill_payments.amount) as total_paid FROM bill_payments GROUP BY bill_payments.cod_prov, bill_payments.nro_doc) AS bill_payments'),
                function($join){
                $join->on('bills_payable.nro_doc', '=', 'bill_payments.nro_doc')
                    ->on('bills_payable.cod_prov', '=', 'bill_payments.cod_prov');
            });

        if ($whereRaw !== ''){
            $query = $query->whereRaw($whereRaw);
        }

        return $query;
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
}
