<?php

/*
 * This file is part of AWS Cognito Auth solution.
 *
 * (c) EllaiSys <support@ellaisys.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sunnydesign\Cognito\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

use Sunnydesign\Cognito\AwsCognitoClient;

use Sunnydesign\Cognito\Exceptions\InvalidUserFieldException;
use Throwable;

trait ForceRegistersUsers
{

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Support\Collection $request
     *
     * @return bool
     * @throws InvalidUserFieldException
     */
    public function forceCreateCognitoUser(Collection $request)
    {
        try {
            //Initialize Cognito Attribute array
            $attributes = [];

            //Get the registration fields
            $userFields = config('cognito.cognito_user_fields');

            //Iterate the fields
            foreach ($userFields as $key => $userField) {
                if ($request->has($userField)) {
                    $attributes[$key] = $request->get($userField);
                } else {
                    Log::error('RegistersUsers:createCognitoUser:InvalidUserFieldException');
                    Log::error("The configured user field {$userField} is not provided in the request.");
                    throw new InvalidUserFieldException("The configured user field {$userField} is not provided in the request.");
                } //End if
            } //Loop ends

            //Register the user in Cognito
            $userKey = $request->has('username') ? 'username' : 'email';

            //Temporary Password parameter
            $password = $request->has('password') ? $request['password'] : null;

            $client = app()->make(AwsCognitoClient::class);

            $client->register($request[$userKey], $password, $attributes);
            $client->confirmSignUp($request[$userKey]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return false;
        }

        return true;
    }

}