<?php

namespace Foodmeup\NutritionBundle\Controller;

use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexControllerService.
 */
class IndexControllerService extends FOSRestController
{
    /**
     * @var mixed
     */
    protected $newObject;

    /**
     * @var ViewHandler FOSRest view handler
     */
    protected $fosRest;

    /**
     * Constructor.
     *
     * @param mixed       $newObject
     * @param ViewHandler $fosRest   FOSRest view handler
     */
    public function __construct($newObject, $fosRest)
    {
        $this->newObject = $newObject;
        $this->fosRest = $fosRest;
    }

    /**
     * INDEX of API.
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->fosRest->handle($this->view($this->newObject, 200));
    }
}
