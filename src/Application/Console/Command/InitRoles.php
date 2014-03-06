<?php

namespace Application\Console\Command;

use Application\Entity\Permission;
use Application\Entity\Role;
use Application\Entity\RolePermission;
use MiniFrame\ConsoleApplication\ApplicationHelper;
use MiniFrame\Extra\Service\DoctrineService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitRoles extends Command
{
    protected function configure()
    {
        $this
            ->setName('application:init-roles')
            ->setDescription('Kullanıcı gruplarını ve izinlerini oluşturur.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var DoctrineService $doctrineService
         * @var ApplicationHelper $applicationHelper
         */
        $applicationHelper = $this->getHelperSet()->get('application');
        $doctrineService = $applicationHelper->getService('doctrine');

        // Rolü oluştur.
        $role = new Role();
        $role->setName('super_user');
        $doctrineService->getEntityManager()->persist($role);

        // İzinleri oluştur
        foreach ($this->getSuperUserPermissions() as $permissionName) {
            $permission = new Permission();
            $permission->setName($permissionName);

            $rolePermission = new RolePermission();
            $rolePermission->setRole($role);
            $rolePermission->setPermission($permission);

            $role->getRolePermissions()->add($rolePermission);
        }

        $doctrineService->getEntityManager()->persist($role);
        $doctrineService->getEntityManager()->flush();
    }

    protected function getSuperUserPermissions()
    {
        return array(
            'admin.login',

            'admin.role.show',
            'admin.role.create',
            'admin.role.update',
            'admin.role.delete',
            'admin.role.list',

            'admin.permission.show',
            'admin.permission.create',
            'admin.permission.update',
            'admin.permission.delete',
            'admin.permission.list',

            'admin.user.show',
            'admin.user.create',
            'admin.user.update',
            'admin.user.delete',
            'admin.user.list',

            'admin.user_activity.list',
            'admin.user_activity.delete',

            'admin.user.profile.show',
            'admin.user.profile.update'
        );
    }
}
