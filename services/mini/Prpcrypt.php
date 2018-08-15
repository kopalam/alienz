<?php
namespace app\services\mini;

/**
 * Prpcrypt class
 *
 *
 */
class Prpcrypt
{
    public $key;


    function __construct( $k )
    {

        $this->key = $k;
    }

  

    /**
     * 对密文进行解密
     * @param string $aesCipher 需要解密的密文
     * @param string $aesIV 解密的初始向量
     * @return string 解密得到的明文
     */
    public function decrypt($aesCipher, $aesIV)
    {
        try {
            $decrypted = openssl_decrypt($aesCipher, 'AES-128-CBC', $this->key, OPENSSL_ZERO_PADDING, $aesIV);
        } catch (Exception $e) {
            return array(ErrorCode::$IllegalBuffer, null);
        }
        try {
            //去除补位字符
            $pkc_encoder = new PKCS7Encoder;
            $result = $pkc_encoder->decode($decrypted);
        } catch (Exception $e) {
            return array(ErrorCode::$IllegalBuffer, null);
        }
        return array(0, $result);
    }

    /**
     * 对密文进行解密
     * @param string $aesCipher 需要解密的密文
     * @param string $aesIV 解密的初始向量
     * @return string 解密得到的明文
     */
//    public function decrypt( $aesCipher, $aesIV )
//    {
//
//        try {
//
//            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
//
//            mcrypt_generic_init($module, $this->key, $aesIV);
//
//            //解密
//            $decrypted = mdecrypt_generic($module, $aesCipher);
//            mcrypt_generic_deinit($module);
//            mcrypt_module_close($module);
//        } catch (\Exception $e) {
//            return array(ErrorCode::$IllegalBuffer, null);
//        }
//
//
//        try {
//            //去除补位字符
//            $pkc_encoder = new PKCS7Encoder;
//            $result = $pkc_encoder->decode($decrypted);
//
//        } catch (\Exception $e) {
//            //print $e;
//            return array(ErrorCode::$IllegalBuffer, null);
//        }
//        return array(0, $result);
//    }

//    /**
//     * 对密文进行解密
//     * @param string $aesCipher 需要解密的密文
//     * @param string $aesIV 解密的初始向量
//     * @return string 解密得到的明文
//     */
//    public function decrypt( $aesCipher, $aesIV )
//    {
//        try {
//            //解密
//            $decrypted = openssl_decrypt(base64_decode($aesCipher), 'AES-256-CBC', base64_decode($this->key), OPENSSL_RAW_DATA, base64_decode($aesIV));
//            // dump(($aesCipher));
//            // dump(($this->key));
//            // dump(($aesIV));
//        } catch (\Exception $e) {
//            return false;
//        }
//
//        try {
//            //去除补位字符
//            $pkc_encoder = new PKCS7Encoder;
//            $result = $pkc_encoder->decode($decrypted);
//        } catch (\Exception $e) {
//            //print $e;
//            return false;
//        }
//        return $result;
//    }
}