<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Idrd\Usuarios\Repo\PersonaInterface;
use App\Modulos\Recreovia\Recreopersona;

class Authenticate
{

    private $repositorio_personas;

    public function __construct(PersonaInterface $repositorio_personas)
    {
        $this->repositorio_personas = $repositorio_personas;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        /*if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }*/

        if(!isset($_SESSION['Usuario']))
        {
            $_SESSION['Usuario'] = '';
        }

        
        if($_SESSION['Usuario'] == '')
        {
            return redirect()->to('/');
        } else {
            $persona = $this->repositorio_personas->obtener($_SESSION['Usuario'][0]);
            $recreopersona = Recreopersona::with('localidades')->where('Id_Persona', $persona['Id_Persona'])->first();

            if ($recreopersona)
            {
                $_SESSION['Usuario']['Recreopersona'] = $recreopersona;
                $_SESSION['Usuario']['Roles'] = [];
                
                foreach ($recreopersona->localidades as $localidad)
                {
                    if (!in_array($localidad->pivot['tipo'], $_SESSION['Usuario']['Roles']))
                        $_SESSION['Usuario']['Roles'][] = $localidad->pivot['tipo'];
                }
            }
        }
        
        return $next($request);
    }
}
