<?php

namespace Modules\Base\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        if(Auth::user()->role=="Teacher"){
            return $next($request);
        }
        elseif(Auth::user()->role=="Student"){            
            return redirect('student');

        }elseif(Auth::user()->role=="Admin"){
            
            return redirect('admin');
        }

        return redirect("login");
    }
}
