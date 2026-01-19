<?php
/**
 * Código executado durante a desinstalação do plugin.
 *
 * Este arquivo é executado quando o plugin é desinstalado (deletado)
 * através do painel administrativo do WordPress.
 *
 * @package InscricoesPagas
 * @since   2.0.0
 */

// Se o uninstall não foi chamado pelo WordPress, abortar
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Limpa todos os dados do plugin.
 *
 * ATENÇÃO: Este código remove permanentemente todos os dados do plugin.
 * Os metadados dos pedidos WooCommerce (inscrito_arbitro, cbx_arbitro, etc.)
 * NÃO são removidos para preservar histórico de pedidos.
 */

// Remover opções do plugin
$options_to_delete = [
    'inscricoes_pagas_version',
    'inscricoes_pagas_activated_at',
    'inscricoes_pagas_deactivated_at',
    'inscricoes_pagas_columns',
    'inscricoes_pagas_per_page',
];

foreach ($options_to_delete as $option) {
    delete_option($option);
}

// Remover transients
global $wpdb;

$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
        '_transient_inscricoes_pagas_%',
        '_transient_timeout_inscricoes_pagas_%'
    )
);

// Para instalações multisite
if (is_multisite()) {
    // Remover opções de cada site
    $sites = get_sites();
    foreach ($sites as $site) {
        switch_to_blog($site->blog_id);

        foreach ($options_to_delete as $option) {
            delete_option($option);
        }

        restore_current_blog();
    }

    // Remover transients do site meta
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s OR meta_key LIKE %s",
            '_site_transient_inscricoes_pagas_%',
            '_site_transient_timeout_inscricoes_pagas_%'
        )
    );
}

// Limpar cache de rewrite rules
flush_rewrite_rules();
