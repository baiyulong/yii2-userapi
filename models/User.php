<?php
namespace uapi\userapi\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\base\Model;

/**
 * User model
 */
class User extends Model implements IdentityInterface
{
    public $user_id;
    public $user_name;
    public $user_password;
    public $user_havechild;
    public $user_nickname;
    public $user_companyid;
    public $user_addtime;
    public $user_lastlogintime;
    public $user_lastloginip;
    public $user_lastloginchild;
    public $user_remarks;
    public $user_isdeleted;
    public $mapping;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $rs = (new Auth())->getInfo(['user_id' => $id]);
        if ($rs['errorCode'] == 0 && isset($rs['data'])) {
            return new static($rs['data']);
        }
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $rs = (new Auth())->getInfo(['user_name' => $username]);
        if ($rs['errorCode'] == 0 && isset($rs['data'])) {
            return new static($rs['data']);
        }
        return null;
    }

    public function getUsername()
    {
        return $this->user_name;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return md5($password) == $this->user_password;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->user_password = md5($password);
    }

    /**
     * 添加用户
     */
    public function add()
    {
        $data = [
            'user_name'      => $this->user_name,
            'user_password'  => $this->user_password,
            'user_havechild' => $this->user_havechild
        ];
        $rs = (new Auth())->add($data);

        if ($rs['errorCode'] == 0 && $rs['data'] > 0) {
            //添加成功后授权给当前产品
            $map = [
                'mapping_userid'    => $rs['data'],
                'mapping_productid' => Yii::$app->params['productid'],
                'mapping_starttime' => time(),
                'mapping_endtime'   => intval(time()) + 30*24*3600,   //新注册用户授权30天
            ];
            $mapRs = (new Mapping())->add($map);
            if ($mapRs['errorCode'] == 0) {
                return $rs['data'];
            }
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() { return null; }
    public function validateAuthKey($authKey) { return true; }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
}
