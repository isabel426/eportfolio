<?php

namespace App\Http\Controllers;

use App\Mail\PruebaCorreo;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Throwable;

class MailController extends Controller
{
    public function prueba() {
        try {
            Mail::to(Auth::user()->email)->send(new PruebaCorreo);
            $retorno = 'success';
            $mensaje = "Mensaje de correo enviado correctamente";
        } catch (Throwable $e) {
            $retorno = 'error';
            $mensaje = $e->getMessage();
        }
            return redirect(route('home'))
            ->with($retorno, $mensaje);
    }
}
