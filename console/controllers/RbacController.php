<?php
namespace console\controllers;

use common\models\User;
use console\rbac\OwnerRule;
use Exception;
use Yii;
use yii\console\Controller;

/**
 * Manages RBAC.
 * @package console\controllers
 */
class RbacController extends Controller
{
    /**
     * Creates RBAC.
     * Command: yii rbac/init
     *
     * @throws \yii\base\Exception
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // creates roles
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        $user = $auth->createRole('user');
        $auth->add($user);

        $guest = $auth->createRole('guest');
        $auth->add($guest);

        // creates permissions
        $read = $auth->createPermission('read');
        $read->description = 'Allows read messages.';
        $auth->add($read);

        $write = $auth->createPermission('write');
        $write->description = 'Allows write massages.';
        $auth->add($write);

        $isAdmin = $auth->createPermission('isAdmin');
        $isAdmin->description = 'Allows manage chat.';
        $auth->add($isAdmin);

        // relates permissions to roles
        // guest can only read messages.
        $auth->addChild($guest, $read);

        // user can red and write messages.
        $auth->addChild($user, $guest);
        $auth->addChild($user, $write);

        // admin can all.
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $isAdmin);

        echo 'Success!';
        return 0;
    }

    /**
     * Assigns role "admin" for user.
     * Command: yii rbac/assign-admin 1
     *
     * @param string $id Primary key of user which should be assigned as "admin".
     * @return int
     * @throws Exception
     */
    public function actionAssignAdmin($id)
    {
        $user = User::findOne($id);
        if(!$user) {
            echo 'User with this ID not found.';
            return 1;
        }

        $auth = Yii::$app->authManager;
        $role = $auth->getRole('admin');
        $auth->assign($role, $id);

        echo 'Success!';
        return 0;
    }
}