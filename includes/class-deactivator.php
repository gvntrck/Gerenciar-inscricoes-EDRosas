<?php
/**
 * Classe Deactivator - Ações de Desativação do Plugin.
 *
 * Esta classe contém todo o código executado durante a desativação do plugin.
 * A desativação não remove dados, apenas limpa caches e transients.
 *
 * @package InscricoesPagas
 * @since   2.0.0
 */

namespace InscricoesPagas;

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Deactivator.
 *
 * @since 2.0.0
 */
class Deactivator
{
    /**
     * Executa ações durante a desativação do plugin.
     *
     * @return void
     */
    public static function deactivate(): void
    {
        // Limpar transients do plugin
        self::clear_transients();

        // Limpar cache de rewrite rules
        flush_rewrite_rules();

        // Registrar data de desativação (para diagnóstico)
        update_option('inscricoes_pagas_deactivated_at', current_time('mysql'));
    }

    /**
     * Remove todos os transients criados pelo plugin.
     *
     * @return void
     */
    private static function clear_transients(): void
    {
        global $wpdb;

        // Remover transients com prefixo do plugin
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
                '_transient_inscricoes_pagas_%',
                '_transient_timeout_inscricoes_pagas_%'
            )
        );

        // Para instalações multisite
        if (is_multisite()) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s OR meta_key LIKE %s",
                    '_site_transient_inscricoes_pagas_%',
                    '_site_transient_timeout_inscricoes_pagas_%'
                )
            );
        }
    }
}
