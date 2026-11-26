<?php
require_once __DIR__ . '/gestion_secrets.php';

function fichier_log(): string
{
    $fichier = $_ENV['LOG_DIR'] ?? getenv('LOG_DIR');
    return ($fichier !== false && $fichier !== '') ? $fichier : '/var/log/php/site-marchand';
}

function nettoyer_log(string $valeur, int $max = 60): string
{
    $valeur = preg_replace('/[\x00-\x1F\x7F]/', ' ', $valeur) ?? '';
    return substr($valeur, 0, $max);
}

function journaliser(string $niveau, string $evenement, string $message): void
{
    $ip = nettoyer_log($_SERVER['REMOTE_ADDR'] ?? 'inconnue', 45);
    $ligne = '[' . date('Y-m-d H:i:s') . '] [' . $niveau . '] [' . $evenement . '] ip=' . $ip . ' ' . $message . PHP_EOL;

    if (@error_log($ligne, 3, fichier_log()) === false) {
        error_log(trim($ligne));
    }
}