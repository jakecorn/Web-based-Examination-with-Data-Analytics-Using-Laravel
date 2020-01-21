<?php

namespace Modules\Base\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRestriction
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
        
        if(Auth::user()->role=="Admin"){
            return $next($request);
        }
        elseif(Auth::user()->role=="Student"){            
            return redirect('student');

        }elseif(Auth::user()->role=="teacher"){
            
            return redirect('teacher');
        }

        return redirect("login");
    }
}
