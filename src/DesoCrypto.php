<?php

namespace DesoSmart\DesoClient;

use Elliptic\EC;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Muvon\KISS\Base58Codec;

final class DesoCrypto
{
    /**
     * @throws DesoCryptoException
     */
    public static function removeNetworkPrefix(string $publicKey): string
    {
        return substr(self::publicKeyDecode($publicKey), 6);
    }

    /**
     * @throws DesoCryptoException
     */
    public static function publicKeyDecode(string $publicKey): string
    {
        $hex = Base58Codec::checkDecode($publicKey);
        if (!$hex) {
            throw new DesoCryptoException('Invalid public key');
        }

        return $hex;
    }

    public static function hasValidJwt(string $publicKey, string $jwt): bool
    {
        try {
            self::validateJwt($publicKey, $jwt);
            return true;
        } catch (Exception $_) {
            return false;
        }
    }

    /**
     * @throws DesoCryptoException
     */
    public static function validateJwt(string $publicKey, string $jwt): void
    {
        $pkHex = self::publicKeyDecode($publicKey);

        try {
            $ec = new EC('secp256k1');
            $pkFull = $ec->keyFromPublic(substr($pkHex, 6), 'hex')->getPublic(false, 'hex');
            $pkBase64 = base64_encode(hex2bin('3056301006072a8648ce3d020106052b8104000a034200'.$pkFull));
            $keyMaterial = sprintf('-----BEGIN PUBLIC KEY-----%s-----END PUBLIC KEY-----', PHP_EOL.$pkBase64.PHP_EOL);
            JWT::decode($jwt, new Key($keyMaterial, 'ES256'));
        } catch (Exception $e) {
            throw new DesoCryptoException('Invalid verify public key', 0, $e);
        }
    }

    public static function signTxnWithDerivedKey(string $transactionHex, string $derivedKeySeedHex): string
    {
        $hash = hash('sha256', hash('sha256', hex2bin($transactionHex), true));
        $privateKey = (new EC('secp256k1'))->keyFromPrivate($derivedKeySeedHex, 'hex');
        $signature = $privateKey->sign($hash, ['canonical' => true]);
        $signature = array_map('chr', $signature->toDER());
        $signature = bin2hex(implode($signature));
        $signature = bin2hex(pack('c', strlen($signature) / 2)).$signature;

        return substr($transactionHex, 0, -2).$signature;
    }
}
