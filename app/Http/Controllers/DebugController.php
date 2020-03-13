<?php

namespace App\Http\Controllers;

use App\Formula;
use App\Models\Houseguest;
use App\Models\User;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DebugController extends Controller
{
    public function showme($blade)
    {
        $vars = $this->getTempVars($blade);
        $blade = preg_replace('/\//', '.', $blade);
        return view($blade, $vars);
    }

    protected function getTempVars($blade): array
    {

        $content = file_get_contents(resource_path("views/{$blade}.blade.php"));

        $pattern = '/{(?:{|!!)[ ]{0,1}\$(?:(\S*?)(?:\[\'(.*?)\'\])?)[ ]{0,1}(?:!!|})}/';

        preg_match_all($pattern, $content, $variables);

        $tempVars = [];

        foreach ($variables[1] as $i => $var) {
            if ($variables[2][$i] !== '') {
                $tempVars[$var][$variables[2][$i]] = "\${$var}['{$variables[2][$i]}']";
            } else {
                $tempVars[$var] = "\${$var}";
            }

        }
        return $tempVars;
    }

    public function xyz()
    {
            Session::flash('error', 'Unable to process request.Error: User Collision has occurred.', true);
            abort(500);
    }
}
