<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Exception\InvalidArgumentException;
use App\Helpers\ObjectUtils;
use DateInterval;

class UserController extends Controller
{



    /**
     * @var ObjectUtils
     */
    protected $objUtils;

    private $cache;

    public function __construct( ObjectUtils $objUtils,AdapterInterface $cacheClient)
    {

        $this->objUtils = $objUtils;
        $this->cache = $cacheClient;
    }

    public function meAction(Request $request)
    {
        $params = json_decode($request->getContent(), true);

        $user = $this->getUser();
        if ($params && count($params))
        {
            $user = $this->objUtils->initialize($user, $params);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $user;
    }

    public function registerAction(Request $request)
    {

        $params = json_decode($request->getContent(), true);
        if (!$params || !count($params) || !isset($params["username"]) || !isset($params["email"]) || !isset($params["password"]))
        {
            throw new InvalidArgumentException("Needs params to register");
        }
        //check email and username
        $email = $this->getDoctrine()->getRepository(User::class)->findOneByEmail($params["email"]);
        if ($email)
        {
            throw new InvalidArgumentException("Email in use");
        }
        //check passwords match
        if ($params["password"] !== $params["password_confirm"])
        {
            throw new InvalidArgumentException("Passwords not match");
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->objUtils->initialize(new User(), $params, ["password"]);
        $user->setPlainPassword($params["password"]);
        $em->persist($user);
        $em->flush();
        return $user;
    }

    public function logoutAction(Request $request)
    {
        $token = $this->get("security.token_storage")->getToken();
        //unregister device

        try {
            $request->getSession()->invalidate();
            $this->get("security.token_storage")->setToken(null);
            return new Response();
        } catch (\Exception $e) {
            return new Response($e, 400);
        }
    }
    public function index()
    {
        $cacheKey = 'my_key';
        $itemCache = $this->cache->getItem($cacheKey);
        $cached = 'no';
        if (!$itemCache->isHit()) {
            $itemCache->set('yes');
            $itemCache->expiresAfter(new DateInterval('PT10S'));
            $this->cache->save($itemCache);
        } else {
            $cached = $itemCache->get();
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'cached' => $cached,
        ]);
    }


}
