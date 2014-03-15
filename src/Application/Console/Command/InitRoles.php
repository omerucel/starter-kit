<?php

namespace Application\Console\Command;

use Application\Entity\Permission;
use Application\Entity\Role;
use Application\Entity\RolePermission;
use MiniFrame\ConsoleApplication\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitRoles extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('application:init-roles')
            ->setDescription('Kullanıcı gruplarını ve izinlerini oluşturur.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Rolü oluştur.
        $role = new Role();
        $role->setName('super_user');
        $this->getEntityManager()->persist($role);

        // İzinleri oluştur
        foreach ($this->getSuperUserPermissions() as $permissionName) {
            $permission = new Permission();
            $permission->setName($permissionName);

            $rolePermission = new RolePermission();
            $rolePermission->setRole($role);
            $rolePermission->setPermission($permission);

            $role->getRolePermissions()->add($rolePermission);
        }

        $this->getEntityManager()->persist($role);
        $this->getEntityManager()->flush();
    }

    protected function getSuperUserPermissions()
    {
        return array(
            'admin.login',
            'admin.logout',
            'admin.dashboard',

            'admin.permissions.group.list',
            'admin.permissions.group.show',
            'admin.permissions.group.save',
            'admin.permissions.group.delete',

            'admin.permissions.list',
            'admin.permissions.show',
            'admin.permissions.save',
            'admin.permissions.delete',

            'admin.roles.list',
            'admin.roles.show',
            'admin.roles.save',
            'admin.roles.delete',

            'admin.users.list',
            'admin.users.show',
            'admin.users.save',
            'admin.users.delete',

            'admin.users.activities.list',
            'admin.users.activities.delete'
        );
    }
}
