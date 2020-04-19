<?php
/**
 * Initialized Class
 * @return Init
 */
use App\Core\Controller;


class DefaultController extends Controller
{
    public function __construct()
    {
        echo "About Controller ";
    }

    public function index()
    {
        echo "index";
    }
}
