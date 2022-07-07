@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2 class="m-0">Registrar</h2>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/horarios/lista">Horarios</a></li>
                        <li class="breadcrumb-item active">Nuevo horario</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            {{-- mensaje al usuario --}}
            @include('layouts.partials.alertas')
            {{-- fin mensaje al usuario --}}

            <div class="row">
                <div class="col-12 col-md-10 offset-md-1">
                    <!-- general form elements -->
                    <div class="card card-secondary mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Nuevo horario</h3>
                        </div>
                        <!-- /.card-header -->

                        <!-- form start -->
                        <form action="{{ route('nuevo-horario') }}" method="post">
                            @csrf
                            <div class="card-body pb-1">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="titulo">Título</label>
                                            <input type="text" class="form-control {{ ($errors->has('titulo')) ? 'is-invalid' : '' }}" name="titulo" id="titulo" value="{{ old('titulo') }}">
                                            @if($errors->has('titulo'))
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $errors->first('titulo') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="tolerancia">Tolerancia (en minutos)</label>
                                            <input type="number" min="0" max="60" step="1" class="form-control {{ ($errors->has('tolerancia')) ? 'is-invalid' : '' }}" name="tolerancia" id="tolerancia" value="{{ old('tolerancia') }}">
                                            @if($errors->has('tolerancia'))
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $errors->first('tolerancia') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="hora_inicio">Hora de entrada</label>
                                            <input type="time" class="form-control {{ ($errors->has('hora_inicio')) ? 'is-invalid' : '' }}" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio') }}">
                                            @if($errors->has('hora_inicio'))
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $errors->first('hora_inicio') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="hora_fin">Hora de salida</label>
                                            <input type="time" class="form-control {{ ($errors->has('hora_fin')) ? 'is-invalid' : '' }}" name="hora_fin" id="hora_fin" value="{{ old('hora_fin') }}">
                                            @if($errors->has('hora_fin'))
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $errors->first('hora_fin') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="cargo_id">Cargo</label>
                                            <select class="select2 form-control {{ ($errors->has('cargo_id')) ? 'is-invalid' : '' }}" name="cargo_id" id="cargo_id">
                                                <option value="">- Seleccionar -</option>
                                                @foreach ($cargos as $cg)
                                                <option value="{{ $cg->id }}" {{ (old('cargo_id')==$cg->id)?'selected':'' }}>
                                                    {{ $cg->nombre_cargo }} - {{ $cg->descripcion }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('cargo_id'))
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $errors->first('cargo_id') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="hora_descanso">Hora inicio descanso</label>
                                            <input type="time" class="form-control {{ ($errors->has('hora_descanso')) ? 'is-invalid' : '' }}" name="hora_descanso" id="hora_descanso" value="{{ old('hora_descanso') }}">
                                            @if($errors->has('hora_descanso'))
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $errors->first('hora_descanso') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group">
                                            <label for="hora_fin_descanso">Hora fin descanso</label>
                                            <input type="time" class="form-control {{ ($errors->has('hora_fin_descanso')) ? 'is-invalid' : '' }}" name="hora_fin_descanso" id="hora_fin_descanso" value="{{ old('hora_fin_descanso') }}">
                                            @if($errors->has('hora_fin_descanso'))
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $errors->first('hora_fin_descanso') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-right">
                                <a href="/horarios/lista" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Registrar</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>


        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

</div>

@endsection
