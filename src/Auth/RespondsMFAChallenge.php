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

use Aws\Result as AWSResult;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

use Sunnydesign\Cognito\AwsCognitoClient;

use Exception;
use Illuminate\Validation\ValidationException;
use Sunnydesign\Cognito\Exceptions\InvalidUserFieldException;
use Sunnydesign\Cognito\Exceptions\AwsCognitoException;

trait RespondsMFAChallenge
{
    public function respondMFAChallenge($request)
    {
        if ($request instanceof Request) {
            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $request = collect($request->all());
        }

        //Create AWS Cognito Client
        $client = app()->make(AwsCognitoClient::class);

        //Responds MFA challenge
        $result = $client->respondMFAChallenge($request['session'], $request['value'], $request['email']);

        if (is_string($result)) {
            return $response = response()->json(['error' => 'cognito.'.$result], 400);
        } else if ($result instanceof AWSResult) {
            return $result['AuthenticationResult'];
        }

        return $result;
    }

    /**
     * Get the respond to MFA challenge validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'session'    => 'required',
            'value'      => 'required|string',
            'email'      => 'required|email',
        ];
    }
}