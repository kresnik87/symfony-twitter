<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Abraham\TwitterOAuth\TwitterOAuth;
use Endroid\Twitter\Client;

class TwitterService
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var string
     */
    protected $consumerKey;
    /**
     * @var string
     */
    protected $consumerSecret;

    /**
     * @param EntityManagerInterface the Doctrine entity Manager
     * @param $consumerKey
     * @param $consumerSecret
     */
    public function __construct(EntityManagerInterface $entityManager, $consumerKey, $consumerSecret)
    {
        $this->em = $entityManager;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
    }

    public function TestApi()
    {
        $twitterOAuth = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
        $client = new Client($twitterOAuth);

// Retrieve the last 50 items in the user's timeline
        $tweets = $client->getTimeline(50);
        return $tweets;
// Or post a status message (with optional media)

    }
}
