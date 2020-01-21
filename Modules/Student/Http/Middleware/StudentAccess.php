<?php

namespace Modules\Student\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAccess
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
        
        if(Auth::user()->role=="Student"){
            return $next($request);
        }
        elseif(Auth::user()->role=="Teacher"){            
            return redirect('teacher');

        }elseif(Auth::user()->role=="Admin"){
            
            return redirect('admin');
        }

        return redirect("login");
    }
}
