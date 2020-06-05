<?php


namespace App\Service;

use ParagonIE\Halite\KeyFactory;
use ParagonIE\HiddenString\HiddenString;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;

class EncryptService
{
    private $encryptKey;

    public function __construct()
    {
        $envEncryptKey = $_ENV['ENCRYPT_KEY'];
        $this->encryptKey = KeyFactory::importEncryptionKey(new HiddenString( $envEncryptKey ));
    }

    //Chiffrement d'un texte de données
    public function encrypt(string $texte): string
    {
        $ciphertext = Symmetric::encrypt( new HiddenString($texte), $this->encryptKey);
        return $ciphertext;
    }

    // Déchiffrement d'un texte chiffré
    public function decrypt(string $ciphertext): string
    {
        $decrypted = Symmetric::decrypt($ciphertext, $this->encryptKey);
        return $decrypted;
    }

    // Génération d'une nouvelle clé secrète
    // Et encodage suous forme de chaine (pour paramètres)
    public function generateNewEncryptKey(): string
    {
        $encryptkey = KeyFactory::generateEncryptionKey();
        $encryptkey = KeyFactory::export($encryptkey)->getString();
        return $encryptkey;
    }
}
