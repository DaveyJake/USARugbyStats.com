<?php

use Behat\MinkExtension\Context\MinkContext;
use Behat\Mink\Driver\Selenium2Driver;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Take screenshot when step fails.
     *
     * @AfterStep
     */
    public function takeScreenshotAfterFailedStep($event)
    {
        if (4 === $event->getResult()) {
            $driver = $this->getSession()->getDriver();
            if (!($driver instanceof Selenium2Driver)) {
                //throw new UnsupportedDriverActionException('Taking screenshots is not supported by %s, use Selenium2Driver instead.', $driver);
                return;
            }

            $failureid = uniqid('failure_');

            file_put_contents("/tmp/{$failureid}.png", $this->getSession()->getDriver()->getScreenshot());

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/upload');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($this->getSession()->getDriver()->getScreenshot())));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . getenv('IMGUR_CLIENT_ID')));
            $result = curl_exec($ch);
            curl_close($ch);

            $imageData = json_decode($result);
            if ($imageData instanceof \stdClass && $imageData->success) {
                $this->printDebug('Screenshot uploaded to imgur: ' . $imageData->data->link . ' (deletehash = ' . $imageData->data->deletehash . ')');
            } else {
                $this->printDebug($result);
            }

            $filename = "/tmp/{$failureid}.html";
            file_put_contents($filename, $this->getSession()->getPage()->getContent());
            $this->printDebug('Page contents dumped to file: ' . $filename);
        }
    }
}
