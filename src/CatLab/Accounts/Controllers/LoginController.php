<?php
/**
 * Created by PhpStorm.
 * User: daedeloth
 * Date: 29/11/14
 * Time: 10:49
 */

namespace CatLab\Accounts\Controllers;

use Neuron\Core\Template;
use Neuron\Net\Response;
use Neuron\URLBuilder;

class LoginController
	extends Base
{
    /**
     * @return Response
     */
	public function login ()
	{
		// Check for return tag
		if ($return = $this->request->input ('return')) {
			$this->request->getSession ()->set ('post-login-redirect', $return);
		}

		// Check for cancel tag
		if ($return = $this->request->input ('cancel')) {
			$this->request->getSession ()->set ('cancel-login-redirect', $return);
		}

		// Check if already registered
		if ($user = $this->request->getUser ('accounts'))
			return $this->module->postLogin ($this->request, $user);

		$template = new Template ('CatLab/Accounts/login.phpt');

		$template->set ('layout', $this->module->getLayout ());
		$template->set ('action', URLBuilder::getURL ($this->module->getRoutePath () . '/login'));
		$template->set ('email', $this->request->input ('email'));

		if ($this->request->getSession ()->get ('cancel-login-redirect')) {
			$template->set ('cancel', URLBuilder::getURL ($this->module->getRoutePath () . '/cancel'));
		}

		$authenticators = $this->module->getAuthenticators ();
		foreach ($authenticators as $v)
		{
			$v->setRequest ($this->request);
		}

		$template->set ('authenticators', $authenticators);

		return Response::template ($template);
	}

    /**
     * @param $token
     * @return Response
     */
	public function authenticator ($token)
	{
		$authenticator = $this->module->getAuthenticators ()->getFromToken ($token);

        if (!$authenticator)
        {
            return Response::error ('Authenticator not found', Response::STATUS_NOTFOUND);
        }

        $authenticator->setRequest ($this->request);

        return $authenticator->login ();
	}

	public function cancel ()
	{
		$cancel = $this->request->getSession ()->get ('cancel-login-redirect');

		if ($cancel)
		{
			$this->request->getSession ()->set ('post-login-redirect', null);
			$this->request->getSession ()->set ('cancel-login-redirect', null);

			return Response::redirect ($cancel);
		}
		else {
			return Response::redirect (URLBuilder::getURL ('/'));
		}
	}

	public function logout ()
	{
		/*
		$template = new Template ('CatLab/Accounts/logout.phpt');

		$template->set ('layout', $this->module->getLayout ());
		$template->set ('action', URLBuilder::getURL ($this->module->getRoutePath () . '/login'));

		return Response::template ($template);
		*/

		return $this->module->logout ($this->request);
	}
}