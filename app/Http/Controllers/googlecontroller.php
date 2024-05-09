<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class googlecontroller extends Controller
{
    public function index()
    {
        $modal = 'https://accounts.google.com/o/oauth2/auth/oauthchooseaccount?client_id=547784346205-4l5pt40p75kok5kgh8sqquftpvhi6kfn.apps.googleusercontent.com&redirect_uri=http%3A%2F%2Foasismanagement.com%3A8000%2Fgoogle-auth%2Fcallback-url&scope=openid%20profile%20email&response_type=code&state=pXvFyQwSIUeeZxUlyeQjMjnkmnR3jhb7ZsIFhAd1&service=lso&o2v=1&ddm=0&flowName=GeneralOAuthFlow'; // URL de la página que deseas mostrar
        return redirect()->away($modal);
    }
}
