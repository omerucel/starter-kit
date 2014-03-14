<?php

namespace Application\Web\Admin\Form;

use Application\Entity\Role;
use Application\Entity\User;
use Application\Repository\RoleRepository;
use Application\Repository\UserRepository;
use MiniFrame\ServiceLoader;
use MiniFrame\WebApplication\BaseForm;
use Respect\Validation\Validator;

class UserSaveForm extends BaseForm
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var int
     */
    public $role;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $surname;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $passwordRepeat;

    /**
     * @var array
     */
    public $allRoles;

    /**
     * @var User
     */
    protected $currentUser;

    /**
     * @var Role
     */
    protected $selectedRole;

    public function __construct(ServiceLoader $serviceLoader)
    {
        parent::__construct($serviceLoader);
        $this->allRoles = $this->getRoleRepository()->findAll();
    }

    public function loadParamsFromRequest()
    {
        $this->id = intval($this->getRequest()->get('id'));
        $this->username = $this->getRequest()->get('username');
        $this->email = $this->getRequest()->get('email');
        $this->name = $this->getRequest()->get('name');
        $this->surname = $this->getRequest()->get('surname');
        $this->password = trim($this->getRequest()->get('password'));
        $this->passwordRepeat = trim($this->getRequest()->get('password_repeat'));
        $this->role = intval($this->getRequest()->get('role'));
    }

    public function loadParamsFromCurrentUser()
    {
        $this->id = intval($this->getRequest()->get('id'));

        if ($this->getCurrentUser() != null) {
            $this->username = $this->getCurrentUser()->getUsername();
            $this->email = $this->getCurrentUser()->getEmail();
            $this->name = $this->getCurrentUser()->getName();
            $this->surname = $this->getCurrentUser()->getSurname();
            $this->role = $this->getCurrentUser()->getRole()->getId();
            $this->selectedRole = $this->getCurrentUser()->getRole();
        }
    }

    /**
     * @return bool
     */
    protected function validateFields()
    {
        $this->validateUsername();
        $this->validateEmail();
        $this->validateRole();
        $this->validatePassword();
    }

    protected function validateUsername()
    {
        // TODO : Karakter kontrolü yapılmalı.
        if (!Validator::create()->notEmpty()->validate($this->username)) {
            $this->setMessage('username', 'empty', 'Kullanıcı adı gerekli.');
        } else {
            if ($this->getUserRepository()->isUsernameUsing($this->username, $this->id)) {
                $this->setMessage('username', 'using', 'Seçtiğiniz kullanıcı adı kullanılıyor.');
            }
        }
    }

    protected function validateEmail()
    {
        if (!Validator::create()->notEmpty()->email()->validate($this->email)) {
            $this->setMessage('email', 'empty', 'E-posta adresi gerekli.');
        } else {
            if ($this->getUserRepository()->isUsernameUsing($this->email, $this->id)) {
                $this->setMessage('email', 'using', 'Seçtiğiniz e-posta adresi kullanılıyor.');
            }
        }
    }

    protected function validateRole()
    {
        if ($this->getCurrentRole() == null) {
            $this->setMessage('role', 'empty', 'Bir rol seçmelisiniz.');
        }
    }

    protected function validatePassword()
    {
        if ($this->getCurrentUser() != null && !$this->isPasswordChanged()) {
            return;
        }

        if (!Validator::create()->notEmpty()->noWhitespace()->validate($this->password)) {
            $this->setMessage('password', 'empty', 'Şifre gerekli.');
        }

        if (!Validator::create()->length(8)->validate($this->password)) {
            $this->setMessage('password', 'min', 'Şifre en az 8 karakter olmalı.');
        }

        if ($this->password !== $this->passwordRepeat) {
            $this->setMessage('password', 'repeat', 'Şifre tekrarı şifre ile aynı olmalı.');
        }
    }

    /**
     * @return bool
     */
    public function isPasswordChanged()
    {
        return strlen($this->password) > 0;
    }

    /**
     * @return User
     */
    public function getCurrentUser()
    {
        if ($this->currentUser == null) {
            $this->currentUser = $this->getUserRepository()->find($this->id);
        }

        return $this->currentUser;
    }

    /**
     * @return Role
     */
    public function getCurrentRole()
    {
        if ($this->selectedRole == null) {
            $this->selectedRole = $this->getRoleRepository()->find($this->role);
        }

        return $this->selectedRole;
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\User');
    }

    /**
     * @return RoleRepository
     */
    public function getRoleRepository()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Role');
    }

    public function clear()
    {
        $this->id = 0;
        $this->username = '';
        $this->email = '';
        $this->name = '';
        $this->surname = '';
        $this->password = '';
        $this->passwordRepeat = '';
    }
}
