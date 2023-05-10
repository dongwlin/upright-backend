<?php
/**
 * Token Service
 */

declare(strict_types=1);

namespace app\api\service;

use app\api\exception\ApiServiceException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;

class TokenService extends BaseService
{
    /** @var JWT */
    protected JWT $jwt;
    /** @var string */
    protected string $key = 'BLnheSYRWaQEjT1zgBTtnpSpP4sjgtNxAKjK50go';
    /** @var int|float 默认token过期时间，当前为1000天 */
    protected int $exp = 86400000;
    /** @var string jwt的接收者*/
    protected string $aud = 'client';
    /** @var string jwt的签发者*/
    protected string $iss = 'server';
//    /** @var int|float 刷新token时间 默认为15天 */
//    protected int $refreshTokenExp = 1296000;
//    /** @var bool token刷新 */
//    protected bool $enableRefreshToken = false;
//    /** @var bool 重复检测 */
//    protected bool $reuseCheck = false;
//    /** @var string 黑名单缓存前缀 */
//    protected string $refreshBlacklistKeyPrefix = 'api_access_token_blacklist_';
//    /** @var string */
//    protected string $loginAgainKeyPrefix = 'api_user_login_again_';

    /**
     * 构造函数
     */
    public function __construct() {
        $this->jwt = new JWT();

        $config = config('api.auth');

        $this->key = $config['jwt_key'] ?? $this->key;
        $this->exp = $config['jwt_exp'] ?? $this->exp;
        $this->aud = $config['jwt_aud'] ?? $this->aud;
        $this->iss = $config['jwt_iss'] ?? $this->iss;
//        $this->enableRefreshToken = $config['enable_refresh_token'] ?? $this->enableRefreshToken;
//        $this->reuseCheck = $config['reuse_check'] ?? $this->reuseCheck;
//        $this->refreshTokenExp = $config['refresh_token_exp'] ?? $this->refreshTokenExp;
    }

    /**
     * 创建token
     * @param string $uid 用户唯一标识
     * @return string
     * @throws ApiServiceException
     */
    public function createToken(string $uid): string {
        $nowTime = time();
        $tokenConfig = [
            'iss' => $this->iss,
            'sub' => '',
            'aud' => $this->aud,
            'exp' => $nowTime + $this->exp,
            'nbf' => $nowTime,
            'iat' => $nowTime,
            'jti' => $this->createJti($uid),
            'uid' => $uid
        ];
        try {
            return JWT::encode($tokenConfig, $this->key, 'HS256');
        } catch (ExpiredException $exception) {  // 签名不正确
            throw new ApiServiceException('签名不正确：'.$exception->getMessage());
        } catch (\Exception $exception) {
            throw new ApiServiceException('其他错误：'.$exception->getMessage());
        }
    }


    /**
     * 检验token
     * @param string $jwt
     * @return array
     */
    public function checkToken(string $jwt = ''): array {
        try {
            JWT::$leeway = 60;  // 当前时间减去60，把时间留点余地，避免多服务器时间有误差，设置leeway后，token的有效时间就是exp+leeway
            $authInfo = (array)JWT::decode($jwt, new Key($this->key, 'HS256'));  // HS256方式，这里要和签发的时候对应
            if (empty($authInfo['uid']) || !array_key_exists('uid', $authInfo))
            {
                return [
                    'code' => 0,
                    'msg' => 'invalid token'
                ];
            }
            return [
                'code' => 1,
                'msg' => 'success',
                'uid' => $authInfo['uid']
            ];
        }
        catch (\InvalidArgumentException $exception) // key为空或格式错误
        {
            return [
                'code' => 0,
                'msg' => 'Provided key/key-array was empty or malformed'
            ];
        }
        catch (\DomainException $exception)  // token格式错误
        {
            return [
                'code' => 0,
                'msg' => 'token is malformed'
            ];
        }
        catch (SignatureInvalidException $exception) {  // 签名不正确
            return [
                'code' => 0,
                'msg' => 'invalid token'
            ];
        }
        catch (BeforeValidException $exception) {  // 签名未生效
            return [
                'code' => 0,
                'msg' => 'invalid token'
            ];
        }
        catch (ExpiredException $exception) {  // token过期
            return [
                'code' => 0,
                'msg' => 'invalid token'
            ];
        }
        catch (\UnexpectedValueException $exception)  // 异常值
        {
            return [
                'code' => 0,
                'msg' => 'invalid token'
            ];
        }
    }

    /**
     * 创建jwt的ID
     * @param string $openid
     * @return string
     */
    public function createJti(string $openid): string
    {
        $time = explode(' ',microtime());
        $micro = substr($time[0], 3, 3);
        return sha1($openid . $time[1] . $micro . uniqid('jwt_' . $openid, true));
    }
}