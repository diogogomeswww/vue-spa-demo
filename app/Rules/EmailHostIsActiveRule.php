<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Log;

class EmailHostIsActiveRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $client = app(\GuzzleHttp\Client::class);

            $domain = explode('@', $value)[1];

            if (in_array($domain, $this->getDomainsWhiteList())) {
                return true;
            }

            $client->head($domain)->getStatusCode();

            return true;
        } catch(\GuzzleHttp\Exception\ConnectException $e) {
            return false;
        } catch (\Throwable $e) {
            Log::error(__METHOD__ . ':' . $e->getMessage(), func_get_args());
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The email must be a valid email address.';
    }

    /**
     * Avoid calls to know/trusted domains
     *
     * @return array
     */
    protected function getDomainsWhiteList()
    {
        // Fetch list from DB/Cache...
        return [
            'google.com',
            'gmail.com',
            'hotmail.com',
            // ...
        ];
    }
}
