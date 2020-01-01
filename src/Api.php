<?php

/**
 * Part of the Zadarma package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Zadarma
 * @version    1.0.0
 * @author     Jose Lorente
 * @license    BSD License (3-clause)
 * @copyright  (c) 2019, Jose Lorente
 */

namespace Jlorente\Zadarma;

use Jlorente\Zadarma\Core\Api as CoreApi;

/**
 * Class Api.
 * 
 * @author Jose Lorente <jose.lorente.martin@gmail.com>
 * @see https://zadarma.com/en/support/api/
 */
class Api extends CoreApi
{

    /**
     * Cleans the array from null and empty values.
     * 
     * @param array $parameters
     * @return array
     */
    protected function filterParameters(array $parameters)
    {
        return array_filter($parameters, function($value) use ($flags) {
            if (in_array($value, [null, ''], true)) {
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Gets the user balance.
     *
     * @return array
     */
    public function getBalance()
    {
        return $this->_get('/v1/info/balance/');
    }

    /**
     * Gets the call rate for the given number in the user's current price plan.
     * 
     * @param string $number The phone number.
     * @param string $callerId Optional caller id which is used to make the call. 
     * @return array
     */
    public function getPrice($number, $callerId = null)
    {
        return $this->_get('/v1/info/price/', $this->filterParameters([
                            'number' => $number
                            , 'caller_id' => $callerId
        ]));
    }

    /**
     * Gets the user's timezone.
     *
     * @return array
     */
    public function getTimezone()
    {
        return $this->_get('/v1/info/timezone/');
    }

    /**
     * Gets information about the user's current price plan.
     *
     * @return array
     */
    public function getTariff()
    {
        return $this->_get('/v1/tariff/');
    }

    /**
     * Stablish a phone call by requesting a callback.
     * 
     * @param string $from The phone/SIP number, the PBX extension number or the PBX scenario, to which the callback is made.
     * @param string $to The phone or SIP number that is being called.
     * @param string $sip SIP user's number or the PBX extension number (for example: 100), 
     * which is used to make the call. The CallerID of this number will be used; 
     * this SIP/PBX extension number will be displayed in the statistics; 
     * call recording and prefix dialling will be used if enabled for this number.
     * @param string $predicted If this flag is specified the request is predicted 
     * (the system calls the “to” number, and only connects it to your SIP, or 
     * your phone number, if the call is successful.)
     * @return array
     * @see https://zadarma.com/en/services/calls/callback/
     */
    public function requestCallback($from, $to, $sip = null, $predicted = null)
    {
        return $this->_get('/v1/request/callback/', $this->filterParameters([
                            'from' => $from
                            , 'to' => $to
                            , 'sip' => $sip
                            , 'predicted' => $predicted
        ]));
    }

    /**
     * Gets the list of user's SIP-numbers.
     *
     * @return array
     */
    public function getSipList()
    {
        return $this->_get('/v1/sip/');
    }

    /**
     * Gets the user's SIP number online status.
     *
     * @return array
     */
    public function getSipStatus($sip)
    {
        return $this->_get("/v1/sip/$sip/status/");
    }

    /**
     * Sets a new number for the the given SIP id.
     * 
     * @param string $id The SIP ID, which needs the CallerID to be changed.
     * @param string $number The new (changed) phone number, in international format (from the list of confirmed or purchased phone numbers.
     * @return array
     */
    public function setSipCallerId($id, $number)
    {
        return $this->_put('/v1/sip/callerid/', [
                    'id' => $id
                    , 'number' => $number
        ]);
    }

    /**
     * Gets the current call forwarding based on the user's SIP numbers.
     * 
     * @param string $id Optional SIP ID to query for.
     * @return array
     */
    public function getSipRedirection($id = null)
    {
        return $this->_get('/v1/sip/redirection/', $this->filterParameters([
                            'id' => $id
        ]));
    }

    /**
     * Switches the status of the call forwarding to on or off for the given SIP id.
     * 
     * @param string $id The SIP ID.
     * @param string $status the call forwarding status on the selected SIP number ("on" or "off").
     * @return array
     */
    public function setSipRedirectionStatus($id, $status)
    {
        return $this->_put('/v1/sip/redirection/', [
                    'id' => $id
                    , 'status' => $status
        ]);
    }

    /**
     * Sets the phone number redirection for the given SIP id.
     * 
     * @param string $id The SIP ID.
     * @param string $type The call forwarding type (e.g. "phone").
     * @param string $number The phone number.
     * @return array
     */
    public function setSipRedirectionNumber($id, $type, $number)
    {
        return $this->_put('/v1/sip/redirection/', [
                    'id' => $id
                    , 'type' => $type
                    , 'number' => $number
        ]);
    }

    /**
     * Gets information about the user's phone numbers.
     * 
     * @return array
     */
    public function getDirectNumbers()
    {
        return $this->_get('/v1/direct_numbers/');
    }

    /**
     * Gets the list of the PBX extension numbers.
     *
     * @return array
     */
    public function getPbxExtensionList()
    {
        return $this->_get('/v1/pbx/internal/');
    }

    /**
     * Gets the online status of the PBX extension number.
     *
     * @return array
     */
    public function getPbxExtensionStatus($pbxId)
    {
        return $this->_get("/v1/pbx/internal/$pbxId/status");
    }

    /**
     * Enables or disables the call recording on the PBX extension number.
     * 
     * @param string $pbxId The PBX extension number.
     * @param string $status status: "on" - switch on, "off" - switch off, 
     * "on_email" - enable the option to send the recordings to the email 
     * address only, "off_email" - disable the option to send the recordings 
     * to the email address only, "on_store" - enable the option to save the 
     * recordings to the cloud, "off_store" - disable the option to save the 
     * recordings to the cloud.
     * @param string $email Changes the email address, where the call 
     * recordings will be sent. You can specify up to 3 email addresses, 
     * separated by comma.
     * @return array
     */
    public function setPbxExtensionRecordingStatus($pbxId, $status, $email = null)
    {
        return $this->_put('/v1/pbx/internal/recording/', $this->filterParameters([
                            'id' => $pbxId
                            , 'status' => $status
                            , 'email' => $email
        ]));
    }

    /**
     * Gets a call record file request.
     * 
     * If only call_id is specified, only one link will be returned and if only 
     * pbx_call_id is specified, several links might be returned.
     * 
     * @param string $callId The call ID, it is specified in the name of the 
     * file with the call recording (unique for every recording).
     * @param string $pbxCallId Permanent ID of the external call to the PBX 
     * (does not alter with the scenario changes, voice menu, etc., it is 
     * displayed in the statistics and notifications);
     * @param string $lifetime
     * @return array
     */
    public function getPbxRecordRequest($callId = null, $pbxCallId = null, $lifetime = null)
    {
        return $this->_get('/v1/pbx/record/request/', $this->filterParameters([
                            'call_id' => $callId
                            , 'pbx_call_id' => $pbxCallId
                            , 'lifetime' => $lifetime
        ]));
    }

    /**
     * Enables and and set up the call forwarding on the PBX extension number.
     * 
     * @param string $pbxNumber The PBX extension number, for example 100.
     * @param string $type The call forwarding type ("voicemail" or "phone").
     * @param string $destination The phone number or email address, depending on the previous parameter.
     * @param string $condition Call forwarding condition ("always" or "noanswer").
     * @param string $setCallerId Set up the called ID during the call forwarding ("on" or "off"). Specified only when type = phone.
     * @param string $voicemailGreeting Notifications about call forwarding  ("no", "standart" or "own"). Specified only when type = voicemail.
     * @param string $greetingFile File with notification in mp3 format or wav below 5 MB. Specified only when type = voicemail and voicemail_greeting = own.
     * @return array
     */
    public function enablePbxRedirection($pbxNumber, $type, $destination, $condition, $setCallerId = null, $voicemailGreeting = null, $greetingFile = null)
    {
        return $this->_post('/v1/pbx/redirection/', $this->filterParameters([
                            'pbx_number' => $pbxNumber
                            , 'status' => 'on'
                            , 'type' => $type
                            , 'destination' => $destination
                            , 'condition' => $condition
                            , 'set_caller_id' => $setCallerId
                            , 'voicemail_greeting' => $voicemailGreeting
                            , 'greeting_file' => $greetingFile
        ]));
    }

    /**
     * Disables the call forwarding on the PBX extension number.
     * 
     * @param string $pbxNumber The PBX extension number, for example 100;
     * @return array
     */
    public function disablePbxRedirection($pbxNumber)
    {
        return $this->_post('/v1/pbx/redirection/', [
                    'pbx_number' => $pbxNumber
                    , 'status' => 'off'
        ]);
    }

    /**
     * Gets the call forwarding info of the PBX extension number.
     *
     * @param string $pbxNumber The PBX extension number, for example 100;
     * @return array
     */
    public function getPbxRedirection($pbxNumber)
    {
        return $this->_get('/v1/pbx/redirection/', [
                    'pbx_number' => $pbxNumber
        ]);
    }

    /**
     * Sends an SMS message to a single or multiple phone numbers.
     * 
     * @param string $number The phone number where to send the SMS message (several numbers can be specified, separated by comma).
     * @param string $message The Message (standard text limit applies; the text will be separated into several SMS messages, if the limit is exceeded).
     * @param string $callerId The optional phone number, from which the SMS messages is sent (can be sent only from list of user's confirmed phone numbers).
     * @return array
     */
    public function sendSms($number, $message, $callerId = null)
    {
        return $this->_post('/v1/sms/send/', $this->filterParameters([
                            'number' => $number
                            , 'message' => $message
                            , 'caller_id' => $callerId
        ]));
    }

    /**
     * Gets overall statistics.
     * 
     * @param string $start The start date of the statistics to search for (format - YYYY-MM-DD HH:MM:SS).
     * @param string $end The end date of the statistics to search for (format - YYYY-MM-DD HH:MM:SS).
     * @param array $parameters Optional parameters in key => value format. See the documentation.
     * @return array
     */
    public function getStatistics($start, $end, array $parameters = [])
    {
        return $this->_get('/v1/statistics/', array_merge([
                    'start' => $start
                    , 'end' => $end
                                ], $parameters));
    }

    /**
     * Gets PBX statistics.
     * 
     * @param string $start The start date of the statistics to search for (format - YYYY-MM-DD HH:MM:SS).
     * @param string $end The end date of the statistics to search for (format - YYYY-MM-DD HH:MM:SS).
     * @param int $version Format of the statistics results (2 - new, 1 - old).
     * @param array $parameters Optional parameters in key => value format. See the documentation.
     * @return array
     */
    public function getPbxStatistics($start, $end, $version = 2, array $parameters = [])
    {
        return $this->_get('/v1/statistics/pbx/', array_merge([
                    'start' => $start
                    , 'end' => $end
                    , 'version' => $version
                                ], $parameters));
    }

    /**
     * Gets the callback widget statistics.
     * 
     * @param string $start The start date of the statistics to search for (format - YYYY-MM-DD HH:MM:SS).
     * @param string $end The end date of the statistics to search for (format - YYYY-MM-DD HH:MM:SS).
     * @param string $widgetId widget Identification; if the parameter is not specified, statistics from all widgets are taken.
     * @return array
     */
    public function getCallbackWidgetStatistics($start, $end, $widgetId = null)
    {
        return $this->_get('/v1/statistics/callback_widget/', $this->filterParameters([
                            'start' => $start
                            , 'end' => $end
                            , 'widget_id' => $widgetId
        ]));
    }

    /**
     * Check the numbers againts the database.
     * 
     * @param string $numbers List of numbers to check in international format. 
     * If numbers contain 1 number the result will be delivered immediately. 
     * If there is a list of numbers the result will be sent to the address 
     * specified on the user database check page or, if the address was not 
     * specified, to the email address.
     * @return array
     */
    public function numbersLookup($numbers)
    {
        return $this->_post('/v1/info/number_lookup/', [
                    'numbers' => $numbers
        ]);
    }

}
