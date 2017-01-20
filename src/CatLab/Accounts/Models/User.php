<?php

namespace CatLab\Accounts\Models;

use CatLab\Accounts\MapperFactory;
use CatLab\Accounts\Module;
use CatLab\Mailer\Mailer;
use CatLab\Mailer\Models\Mail;
use DateTime;
use Neuron\Collections\Collection;
use Neuron\Config;
use Neuron\Core\Template;
use Neuron\Tools\Text;
use Neuron\Tools\TokenGenerator;
use Neuron\URLBuilder;

/**
 * Class User
 * @package CatLab\Accounts\Models
 */
class User implements \Neuron\Interfaces\Models\User
{

    /**
     * @var int $id
     */
    private $id;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $passwordhash
     */
    private $passwordhash;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var boolean
     */
    private $emailVerified;

    public function __construct()
    {

    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param string $hash
     */
    public function setPasswordHash($hash)
    {
        $this->passwordhash = $hash;
    }

    /**
     * @return string
     */
    public function getPasswordHash()
    {
        return $this->passwordhash;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return boolean
     */
    public function isEmailVerified()
    {
        return $this->emailVerified;
    }

    /**
     * @param boolean $emailVerified
     * @return self
     */
    public function setEmailVerified($emailVerified)
    {
        $this->emailVerified = $emailVerified;
        return $this;
    }

    /**
     * @param null $type The type of user we are requesting.
     * @return Collection
     */
    public function getDeligatedAccounts($type = null)
    {
        return MapperFactory::getDeligatedMapper()->getFromUser($this, $type);
    }

    /**
     * @param Module $module
     */
    public function sendVerificationEmail(Module $module)
    {
        return;

        $email = new Email ();
        $email->setEmail($this->getEmail());
        $email->setExpires(new DateTime ('next week'));
        $email->setToken(TokenGenerator::getSimplifiedToken(24));
        $email->setUser($this);
        $email->setVerified(false);

        MapperFactory::getEmailMapper()->create($email);

        $template = new Template ('CatLab/Accounts/mails/verification.phpt');
        $template->set('user', $this);
        $template->set('verify_url', $email->getVerifyURL($module->getRoutePath()));

        $mail = new Mail ();
        $mail->setSubject(Text::getInstance()->getText('Email address verification'));
        $mail->setTemplate($template);
        $mail->getTo()->add($this->getEmail());
        $mail->setFrom(Config::get('mailer.from.email'));

        Mailer::getInstance()->send($mail);

    }

    /**
     * @param Module $module
     */
    public function sendConfirmationEmail(Module $module)
    {
        return;

        $template = new Template ('CatLab/Accounts/mails/confirmation.phpt');
        $template->set('user', $this);

        $mail = new Mail ();
        $mail->setSubject(Text::getInstance()->getText('Email address confirmed'));
        $mail->setTemplate($template);
        $mail->getTo()->add($this->getEmail());
        $mail->setFrom(Config::get('mailer.from.email'));

        Mailer::getInstance()->send($mail);
    }

    /**
     * @param Module $module
     */
    public function sendPasswordRecoveryEmail(Module $module)
    {
        $passwordRecoveryRequest = new PasswordRecovery();
        $passwordRecoveryRequest->setExpires(new DateTime ('next week'));
        $passwordRecoveryRequest->setToken(TokenGenerator::getSimplifiedToken(24));
        $passwordRecoveryRequest->setUser($this);

        MapperFactory::getPasswordRecoveryMapper()->create($passwordRecoveryRequest);

        $template = new Template('CatLab/Accounts/mails/passwordRecovery.phpt');
        $template->set('user', $this);
        $template->set('recovery_url', $passwordRecoveryRequest->getUrl($module->getRoutePath()));

        $mail = new Mail ();
        $mail->setSubject(Text::getInstance()->getText('Password recovery'));
        $mail->setTemplate($template);
        $mail->getTo()->add($this->getEmail());
        $mail->setFrom(Config::get('mailer.from.email'));

        Mailer::getInstance()->send($mail);
    }
}