<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\Security;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property int $id Идентификатор записи (неизменяемое)
 * @property string $username Имя пользователя
 * @property string $password Пароль
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $old_password;
    public $new_password;
    public $repeat_password;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'old_password', 'new_password', 'repeat_password' ], 'required', 'on' => 'changePassword'],
            [[ 'old_password' ], 'findPasswords', 'on' => 'changePassword' ],
            [[ 'repeat_password' ], 'compare', 'compareAttribute' => 'new_password', 'on' => 'changePassword' ],
            [[ 'username', 'password' ], 'required' ],
            [[ 'username', 'password' ], 'string', 'max' => 128 ],
            [[ 'username' ], 'unique' ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'       => Yii::t('app', 'Identificator'),
            'username' => Yii::t('users', 'User name'),
            'password' => Yii::t('users', 'Password'),
            'old_password' => Yii::t('users', 'Old password'),
            'new_password' => Yii::t('users', 'New password'),
            'repeat_password' => Yii::t('users', 'repeat password'),
        ];
    }

    // Поиск пользователя по идентификатору
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    // Поиск пользователя по ключу доступа
    public static function findIdentityByAccessToken($token, $type = NULL)
    {
        return static::findOne([ 'access_token' => $token ]);
    }

    // Поиск пользователя по имени
    public static function findByUsername($username)
    {
        return static::findOne([ 'username' => $username ]);
    }

    // Поиск пользователя по уникальному ключу сброса пароля
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params[ 'user.passwordResetTokenExpire' ];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time())
        {
            // Время токена истекло
            return null;
        }
        return static::findOne([ 'password_reset_token' => $token ]);
    }

    // Получение идентификатора пользователя
    public function getId()
    {
        return $this->id;
    }

    // Получение ключа авторизации
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    // Проверка ключа авторизации
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function findPasswords($attribute, $params)
    {
        if (! $this->validatePassword($this->old_password))
            $this->addError($attribute, Yii::t('users', 'Old password is incorrect.'));
    }

    // Проверка пароля пользователя
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    // Устанока пароля пользователя
    public function setPassword($password)
    {
        $this->password = Yii::$app->getSecurity()->generatePasswordHash($password);
    }

    // Создание уникального ключа авторизации
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
    }

    // Создание ключа сброса пароля
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = utf8_encode(Yii::$app->getSecurity()->generateRandomString()) . '_' . time();
    }

    // Сброс ключа сброса пароля
    public function removePasswordResetToken()
    {
        $this->password_reset_token = NULL;
    }

    // Проверка доступа
    public static function canPermission($permission)
    {
        return isset(Yii::$app->authManager->getPermissionsByUser(\Yii::$app->user->getId())[$permission]);
    }

    // Перед записью генерируем для новых записей уникальный ключ
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            if ($this->isNewRecord)
            {
                 $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }  // */
}
