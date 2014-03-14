<?php

return array(
    '/' => 'Application\Web\Site\HomePage',

    // Admin General
    '/admin' => 'Application\Web\Admin\Dashboard',
    '/admin/login' => 'Application\Web\Admin\Login',
    '/admin/logout' => 'Application\Web\Admin\Logout',

    // Admin Permissions Group
    '/admin/permission-groups' => 'Application\Web\Admin\PermissionGroups',
    '/admin/permission-groups/save' => 'Application\Web\Admin\PermissionGroupSave',

    // Admin Permissions
    '/admin/permissions' => 'Application\Web\Admin\Permissions',
    '/admin/permissions/save' => 'Application\Web\Admin\PermissionSave',

    // Admin Roles
    '/admin/roles' => 'Application\Web\Admin\Roles',
    '/admin/roles/save' => 'Application\Web\Admin\RoleSave',
);
