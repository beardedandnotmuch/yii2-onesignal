<?php

namespace beardedandnotmuch\onesignal;

use Yii;
use yii\base\Exception;
use yii\base\Component as BaseCompnent;
use OneSignal\Config;
use OneSignal\OneSignal;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Http\Client\Common\HttpMethodsClient as HttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;

class Component extends BaseCompnent
{
    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $apiKey;

    /**
     * @var string
     */
    public $userAuthKey;

    /**
     * @var OneSignal
     */
    protected $api;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (empty($this->appId)) {
            throw new Exception('Configure onesignal appId!');
        }

        if (empty($this->apiKey)) {
            throw new Exception('Configure onesignal apiKey!');
        }

        $config = new Config();
        $config->setApplicationId($this->appId);
        $config->setApplicationAuthKey($this->apiKey);
        if (!empty($this->userAuthKey)) {
            $config->setUserAuthKey($this->userAuthKey);
        }

        $guzzle = new GuzzleClient([
            // ..config
        ]);

        $client = new HttpClient(new GuzzleAdapter($guzzle), new GuzzleMessageFactory());

        $this->api = new OneSignal($config, $client);
    }

    /**
     * undocumented function
     *
     * @return OneSignal\Apps
     * @throw Exception if userAuthKey is not set.
     */
    public function apps()
    {
        if (empty($this->userAuthKey)) {
            throw new Exception('You are not allowed to use Apps REST API while userAuthKey is empty');
        }

        return $this->api->apps;
    }

    /**
     * undocumented function
     *
     * @return OneSignal\Devices
     */
    public function devices()
    {
        return $this->api->devices;
    }

    /**
     * undocumented function
     *
     * @return OneSignal\Notifications
     */
    public function notifications()
    {
        return $this->api->notifications;
    }

}
