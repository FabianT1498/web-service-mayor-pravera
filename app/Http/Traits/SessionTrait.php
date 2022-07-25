<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;

trait SessionTrait {

    public function checkIfSessionIsSet(Request $request, $session_key, $val){
        
        $value = $request->session()->get($session_key);

        return $request->session()->has($session_key) && $value === $val;
    }

    public function setSession(Request $request, $session_key, $val){

        if (!$this->checkIfSessionIsSet($request, $session_key, $val)){
            $request->session()->put($session_key, $val);
        }
    }
}