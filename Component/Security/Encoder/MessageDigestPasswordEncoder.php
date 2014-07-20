<?php

namespace Tigreboite\FunkylabBundle\Component\Security\Encoder;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;

/**
 * Original code from http://appventus.com/blog/migration-des-utilisateurs-symfony-1-4-vers-symfony2-sfguarduser-to-fosuserbundle
 */
class MessageDigestPasswordEncoder extends BasePasswordEncoder
{
    /**
     * @var string
     */
    protected $algorithm;

    /**
     * @var bool
     */
    protected $encodeHashAsBase64;

    /**
     * @var int
     */
    protected $iterations;

    /**
     * Constructor.
     *
     * @param string $algorithm The digest algorithm to use
     * @param bool $encodeHashAsBase64 Whether to base64 encode the password hash
     * @param integer $iterations The number of iterations to use to stretch the password hash
     */
    public function __construct($algorithm, $encodeHashAsBase64, $iterations)
    {
        $this->algorithm = $algorithm;
        $this->encodeHashAsBase64 = $encodeHashAsBase64;
        $this->iterations = $iterations;
    }

    /**
     * Encodes the raw password.
     *
     * @param string $raw The password to encode
     * @param string $salt The salt
     *
     * @return string The encoded password
     *
     * @throws \LogicException
     */
    public function encodePassword($raw, $salt)
    {
        if (!in_array($this->algorithm, hash_algos(), true)) {
            throw new \LogicException(sprintf('The algorithm "%s" is not supported.', $this->algorithm));
        }

        $salted = $this->mergePasswordAndSalt($raw, $salt);
        $digest = hash($this->algorithm, $salted, true);

        for ($i = 1; $i < $this->iterations; $i++) {
            $digest = hash($this->algorithm, $digest.$salted, true);
        }

        return $this->encodeHashAsBase64 ? base64_encode($digest) : bin2hex($digest);
    }

    /**
     * Checks a raw password against an encoded password.
     *
     * @param string $encoded An encoded password
     * @param string $raw A raw password
     * @param string $salt The salt
     *
     * @return Boolean true if the password is valid, false otherwise
     */
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $this->comparePasswords($encoded, $this->encodePassword($raw, $salt));
    }

    /**
     * @param string $password
     * @param string $salt
     *
     * @return string
     */
    protected function mergePasswordAndSalt($password, $salt)
    {
        if (empty($salt)) {
            return $password;
        }

        return !$this->algorithm || 'sha1' === $this->algorithm ? $salt.$password : $password."{".$salt."}";
    }
}