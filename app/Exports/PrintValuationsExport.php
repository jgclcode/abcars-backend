<?php

namespace App\Exports;

use App\Models\Sell_your_car;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use Maatwebsite\Excel\Concerns\FromCollection;

class PrintValuationsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $iduservaluator;
    private $datePrintVal;
    private $datePrintEndVal;

    public function setIdUser(int $iduservaluator, String $datePrintVal, String $datePrintEndVal)
    {
        $this->iduservaluator = $iduservaluator;
        $this->datePrintVal = $datePrintVal;
        $this->datePrintEndVal = $datePrintEndVal;
    }

    public function view(): View
    {

        if ($this->iduservaluator != 0 && $this->datePrintVal != '' && $this->datePrintEndVal != '') {
            // $month = date("m", $this->datePrintVal); /** 11 */
            // $year  = date("Y", $this->datePrintVal); /** 2022 */
            $from = date($this->datePrintVal);
            $to = date($this->datePrintEndVal);
        }else if($this->iduservaluator == 0 && $this->datePrintVal != '' && $this->datePrintEndVal != ''){
            $from = date($this->datePrintVal);
            $to = date($this->datePrintEndVal);
        }

        if ($this->iduservaluator == null && $this->datePrintVal == '' && $this->datePrintEndVal == '') {
            $dateNowPrintValuation = Sell_your_car::whereIn('sell_your_cars.status', ['stand_by', 'to_valued', 'valued'])
                                                    ->join('sell_your_car_valuator','sell_your_car_valuator.sell_your_car_id', '=', 'sell_your_cars.id')
                                                    ->join('valuators', 'valuators.id', '=', 'sell_your_car_valuator.valuator_id')
                                                    ->join('users', 'users.id', '=', 'valuators.user_id')
                                                    ->join('check_lists', 'check_lists.sell_your_car_id', '=', 'sell_your_cars.id')
                                                    ->join('users AS usersT', 'usersT.id', '=', 'check_lists.technician_id')
                                                    ->select(
                                                        'sell_your_cars.id','sell_your_cars.vin', 'sell_your_cars.status', 'users.name AS valuator_name', 'users.surname AS valuator_lastname',
                                                            'users.email', 'usersT.name AS technician_name', 'usersT.surname AS technician_lastname', 'sell_your_cars.created_at'
                                                        )
                                                    ->distinct()
                                                    ->get();
    
            return view('print_valuation.printvaluationcsv', ['dateNowPrintValuation' => $dateNowPrintValuation]);
        }else if($this->iduservaluator != null && $this->datePrintVal == '' && $this->datePrintEndVal == '') {
            $dateNowPrintValuation = Sell_your_car::whereIn('sell_your_cars.status', ['stand_by', 'to_valued', 'valued'])
                                                    ->join('sell_your_car_valuator','sell_your_car_valuator.sell_your_car_id', '=', 'sell_your_cars.id')
                                                    ->join('valuators', 'valuators.id', '=', 'sell_your_car_valuator.valuator_id')
                                                    ->join('users', 'users.id', '=', 'valuators.user_id')
                                                    ->join('check_lists', 'check_lists.sell_your_car_id', '=', 'sell_your_cars.id')
                                                    ->join('users AS usersT', 'usersT.id', '=', 'check_lists.technician_id')
                                                    // ->whereYear('sell_your_cars.created_at', date($year)) /** date('2022') */
                                                    // ->whereMonth('sell_your_cars.created_at', date($month)) /** date('11') */
                                                    ->where('users.id', $this->iduservaluator)
                                                    ->select(
                                                        'sell_your_cars.id','sell_your_cars.vin', 'sell_your_cars.status', 'users.name AS valuator_name', 'users.surname AS valuator_lastname',
                                                            'users.email', 'usersT.name AS technician_name', 'usersT.surname AS technician_lastname', 'sell_your_cars.created_at'
                                                        )
                                                    ->distinct()
                                                    ->get();
    
            return view('print_valuation.printvaluationcsv', ['dateNowPrintValuation' => $dateNowPrintValuation]);
        }else if ($this->iduservaluator == null && $this->datePrintVal != '' && $this->datePrintEndVal != '') {
            // dd('Entro al tecer if', $from, $to);
            $dateNowPrintValuation = Sell_your_car::whereIn('sell_your_cars.status', ['stand_by', 'to_valued', 'valued'])
                                                    ->join('sell_your_car_valuator','sell_your_car_valuator.sell_your_car_id', '=', 'sell_your_cars.id')
                                                    ->join('valuators', 'valuators.id', '=', 'sell_your_car_valuator.valuator_id')
                                                    ->join('users', 'users.id', '=', 'valuators.user_id')
                                                    ->join('check_lists', 'check_lists.sell_your_car_id', '=', 'sell_your_cars.id')
                                                    ->join('users AS usersT', 'usersT.id', '=', 'check_lists.technician_id')
                                                    ->whereBetween('sell_your_cars.created_at', [$from, $to])
                                                    ->select(
                                                        'sell_your_cars.id','sell_your_cars.vin', 'sell_your_cars.status', 'users.name AS valuator_name', 'users.surname AS valuator_lastname',
                                                            'users.email', 'usersT.name AS technician_name', 'usersT.surname AS technician_lastname', 'sell_your_cars.created_at'
                                                        )
                                                    ->distinct()
                                                    ->get();
    
            return view('print_valuation.printvaluationcsv', ['dateNowPrintValuation' => $dateNowPrintValuation]);
        }elseif ($this->iduservaluator != null && $this->datePrintVal != '' && $this->datePrintEndVal != '') {
            $dateNowPrintValuation = Sell_your_car::whereIn('sell_your_cars.status', ['stand_by', 'to_valued', 'valued'])
                                                    ->join('sell_your_car_valuator','sell_your_car_valuator.sell_your_car_id', '=', 'sell_your_cars.id')
                                                    ->join('valuators', 'valuators.id', '=', 'sell_your_car_valuator.valuator_id')
                                                    ->join('users', 'users.id', '=', 'valuators.user_id')
                                                    ->join('check_lists', 'check_lists.sell_your_car_id', '=', 'sell_your_cars.id')
                                                    ->join('users AS usersT', 'usersT.id', '=', 'check_lists.technician_id')
                                                    ->whereBetween('sell_your_cars.created_at', [$from, $to])
                                                    ->where('users.id', $this->iduservaluator)
                                                    ->select(
                                                        'sell_your_cars.id','sell_your_cars.vin', 'sell_your_cars.status', 'users.name AS valuator_name', 'users.surname AS valuator_lastname',
                                                            'users.email', 'usersT.name AS technician_name', 'usersT.surname AS technician_lastname', 'sell_your_cars.created_at'
                                                        )
                                                    ->distinct()
                                                    ->get();
    
            return view('print_valuation.printvaluationcsv', ['dateNowPrintValuation' => $dateNowPrintValuation]);
        }
    }
}
