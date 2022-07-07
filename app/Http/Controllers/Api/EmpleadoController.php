<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cargo;
use App\Models\Horario;
use App\Models\Empleado;
use App\Models\Asistencia;
use Carbon\Carbon;
use Validator;

class EmpleadoController extends Controller
{
    public function showData()
    {
        $horarios = Horario::orderBy('created_at', 'DESC')
            ->where('estado', 1)->get();
        $cargos = Cargo::orderBy('created_at', 'DESC')
            ->where('estado', 1)->get();

        $data = [
            'horarios' => $horarios,
            'cargos' => $cargos
        ];
        return response()->json($data, 200);
    }
    public function registerEmpl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => 'required',
            'apellidos' => 'required',
            'direccion' => 'required',
            'tel_cel' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:6',
            'fecha_nacimiento' => 'required',
            'genero' => 'required',
            'cargo_id' => 'required',
            'horario_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        $tmpDate = explode('/', strval($request->fecha_nacimiento));
        $tmpDate2 = $tmpDate[0].'/'.$tmpDate[1].'/'.$tmpDate[2];
        $time = strtotime($tmpDate2);
        $newformat = date('Y-m-d',$time);

        $empleado = new Empleado;
        $empleado->nombres = $request->nombres;
        $empleado->apellidos = $request->apellidos;
        $empleado->direccion = $request->direccion;
        $empleado->tel_cel = $request->tel_cel;
        $empleado->fecha_nacimiento = $newformat;
        $empleado->genero = $request->genero;
        $empleado->cargo_id = $request->cargo_id;
        $empleado->horario_id = $request->horario_id;
        $empleado->save();

        $strCode = '';
        for ($i=0; $i < (3-strlen(strval($empleado->id))); $i++) {
            $strCode = $strCode.'0';
        }
        $empleado->cod_empleado = 'PE' . $strCode . $empleado->id;
        $empleado->save();

        return response()->json('ok', 200);
    }
    public function listEmpleados()
    {
        $lista = Empleado::orderBy('created_at', 'DESC')
            ->with('cargo')
            ->get();

        return response()->json($lista, 200);
    }
    /**
     * Marcar asistencia
     */
    public function Asistencia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cod_empleado' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        if (!Empleado::where('cod_empleado', $request->cod_empleado)->exists()) {
            return response()->json('back', 102);
        }

        $emp = Empleado::select('id', 'cod_empleado')
            ->where('cod_empleado', $request->cod_empleado)
            ->first();

        $listaHorarios = Horario::where('empleado_id', $emp->id)
            ->where('dia', date('D'))
            ->get();

        if (count($listaHorarios) <= 0) {
            return response()->json('error', 103);
        }

        // Hora del usuario
        $tmpHora = Carbon::create($request->hora);
        $fechaHoy = (date('Y-m-d'))?Carbon::parse(date('Y-m-d'))->format('Y-m-d H:i:s'):'';
        $ultimoReg = Asistencia::select('id', 'hora')
            ->where('empleado_id', $emp->id)
            ->where(function($x) use($fechaHoy) {
                if ($fechaHoy!=='') {
                    $x->where('fecha', '>=', $fechaHoy);
                    $x->where('fecha', '<=', $fechaHoy);
                }
            })
            ->orderBy('created_at', 'DESC')
            ->first();
        $horaReg = ($ultimoReg) ? Carbon::create($ultimoReg->hora) : '';
        $diferencia = ($horaReg!='') ? $horaReg->DiffInMinutes($tmpHora) : 100;
        if ($diferencia > 5) {
            // return response()->json('ok', 200);
        } else {
            return response()->json('tmp', 403);
        }

        // cantidad ya marcadas
        $now = Carbon::parse(date('Y-m-d'))->format('Y-m-d H:i:s');
        $cantAssis = Asistencia::where('empleado_id', $emp->id)
            ->where(function($x) use($now) {
                $x->where('fecha', '>=', $now);
                $x->where('fecha', '<=', $now);
            })->count();

        // cantidad que debe marcar
        $cantMark = 0;
        $arrayHrs = array(); 
        foreach ($listaHorarios as $itemHr) {
            $cantMark += ($itemHr->hora_inicio)?1:0;
            $cantMark += ($itemHr->hora_fin)?1:0;

            array_push($arrayHrs, ['inicio' => $itemHr->hora_inicio, 'fin' => $itemHr->hora_fin]);
        }

        $nowTime = Carbon::parse(date('H:i:s'))->format('H:i:s');
        $timestamp = strtotime($nowTime);
        $diff = null;
        $i = null;
        $j = null;

        foreach ($arrayHrs as $a => $row) {
            foreach ($row as $b => $time) {
                $currDiff = abs($timestamp - strtotime($time));
                if (is_null($diff) || $currDiff < $diff) {
                    $i = $a;
                    $j = $b;
                    $diff = $currDiff;
                }
            }
        }

        $minutes = ($diff / 60);
        if ($minutes >= 20) {
            return response()->json('error', 104);
        }

        // verificar horario actual y tipo
        $tmpHorario = null;
        $tipoHorario = null;

        $tmpHorario = $arrayHrs[$i][$j];
        $tipoHorario = $j;

        // marcar
        $fechaHoy = (date('Y-m-d'))?Carbon::parse(date('Y-m-d'))->format('Y-m-d H:i:s'):'';
        $tipo = '';
        if ($cantAssis < $cantMark) {
            $mark = new Asistencia;
            $mark->empleado_id = $emp->id;
            $mark->fecha = $fechaHoy;
            $mark->hora = Carbon::create($request->hora);
            $mark->horario = $tmpHorario;
            $mark->tipo = $tipoHorario;
            $mark->save();
        } else {
            return response()->json('info', 422);
        }

        if ($cantAssis%2 == 0) {
            $tipo = 'entrada';
        }
        if ($cantAssis%2 != 0) {
            $tipo = 'salida';
        }

        return response()->json('ok', 200);
    }
}
