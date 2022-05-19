<?php

namespace DesoSmart\DesoClient;

use Elliptic\EC;
use Muvon\KISS\Base58Codec;

final class DesoCrypto
{
    static public function prepareDerivedKeyForExtraData(string $derivedPublicKey): string
    {
        // Truncate network prefix
        return substr(Base58Codec::checkDecode($derivedPublicKey), 6);
    }

    static public function signTxnWithDerivedKey(string $transactionHex, string $derivedKeySeedHex): string
    {
        $hash = hash('sha256', hash('sha256', hex2bin($transactionHex), true));
        $privateKey = (new EC('secp256k1'))->keyFromPrivate($derivedKeySeedHex, 'hex');
        $signature = $privateKey->sign($hash, ['canonical' => true]);
        $signature = array_map('chr', $signature->toDER());
        $signature = bin2hex(implode($signature));
        $signature = bin2hex(pack('c', strlen($signature) / 2)) . $signature;

        return substr($transactionHex, 0, -2) . $signature;
    }
}
