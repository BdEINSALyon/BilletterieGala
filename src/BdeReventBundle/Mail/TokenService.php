<?php
/**
 * Created by PhpStorm.
 * User: pvienne
 * Date: 27/11/15
 * Time: 18:18
 */

namespace BdeReventBundle\Mail;


class TokenService
{
    private $_secret;

    /**
     * TokenService constructor.
     * @param $_secret
     */
    public function __construct($_secret)
    {
        $this->_secret = $_secret;
    }

    public function decrypt_data($data)
    {
        $data = preg_split('/\./i', $data);
        $encrypted = ($data[1]);
        if ($encrypted != $this->hash($data[0]))
            return null;
        return intval($data[0]);
    }

    /**
     * @param $value
     * @return string
     */
    public function hash($value)
    {
        return strtoupper(substr(md5(sha1($this->_get_secret_hash() . $value . $this->_get_secret_hash())), 0, 9));
    }

    private function _get_secret_hash()
    {
        $md5_s = md5($this->_secret);
        $sha1_s = sha1($this->_secret);
        $md5_md5_s = md5($md5_s);
        return
            sha1($md5_md5_s) . sha1($sha1_s . $md5_md5_s . $md5_s);
    }

    public function crypt_data($participant_id)
    {
        return $participant_id . '.' . $this->hash($participant_id);
    }
} 