<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillPayable extends Model
{
   
    public $incrementing = false;
    protected $connection = 'web_services_db';
    protected $table = 'bills_payable';
    public $timestamps = false;
    protected $primaryKey = ['nro_doc','cod_prov'];

    protected $fillable = [
        'nro_doc',
        'cod_prov',
        'bill_type',
        'amount',
        'tasa',
        'is_dollar',
        'status',
        'emission_date'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->nro_doc = key_exists('nro_doc', $attributes) ? $attributes['nro_doc'] : '';
        $this->cod_prov = key_exists('cod_prov', $attributes) ? $attributes['cod_prov'] : '';
        $this->descrip_prov =  key_exists('descrip_prov', $attributes) ? $attributes['descrip_prov'] : '';
        $this->bill_type = key_exists('bill_type', $attributes) ? $attributes['bill_type'] : '';
        $this->amount = key_exists('amount', $attributes) ? $attributes['amount'] : '';
        $this->is_dollar = key_exists('is_dollar', $attributes) ? $attributes['is_dollar'] : '';
        $this->status = key_exists('status', $attributes) ? $attributes['status'] : array_keys(config('constants.BILL_PAYABLE_STATUS'))[0];
        $this->tasa = key_exists('tasa', $attributes) ? $attributes['tasa'] : 0;
        $this->emission_date = key_exists('emission_date', $attributes) ? $attributes['emission_date'] : '';
        $this->bill_payable_schedules_id = key_exists('bill_payable_schedules_id', $attributes) ? $attributes['bill_payable_schedules_id'] : null;
    }

    protected function getKeyForSaveQuery()
    {

        $primaryKeyForSaveQuery = array(count($this->primaryKey));

        foreach ($this->primaryKey as $i => $pKey) {
            $primaryKeyForSaveQuery[$i] = isset($this->original[$this->getKeyName()[$i]])
                ? $this->original[$this->getKeyName()[$i]]
                : $this->getAttribute($this->getKeyName()[$i]);
        }

        return $primaryKeyForSaveQuery;

    }

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {

        foreach ($this->primaryKey as $i => $pKey) {
            $query->where($this->getKeyName()[$i], '=', $this->getKeyForSaveQuery()[$i]);
        }

        return $query;
    }
}
