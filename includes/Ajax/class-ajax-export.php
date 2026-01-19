<?php
/**
 * Classe Ajax_Export - Handler para Exportação CSV.
 *
 * Gerencia as requisições AJAX para exportar inscrições para CSV.
 *
 * @package InscricoesPagas\Ajax
 * @since   2.0.0
 */

namespace InscricoesPagas\Ajax;

use InscricoesPagas\Helpers\Meta_Extractor;
use InscricoesPagas\Helpers\CSV_Exporter;

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Ajax_Export.
 *
 * @since 2.0.0
 */
class Ajax_Export extends Ajax_Base
{
    /**
     * Processa a requisição AJAX para exportação.
     *
     * @return void
     */
    public function handle(): void
    {
        // Verificar login (nonce via GET para download)
        if (!is_user_logged_in()) {
            wp_die(
                esc_html__('Você precisa estar logado para realizar esta ação.', 'inscricoes-pagas'),
                esc_html__('Erro', 'inscricoes-pagas'),
                ['response' => 403]
            );
            return;
        }

        // Verificar capabilities
        if (!current_user_can('manage_woocommerce')) {
            wp_die(
                esc_html__('Você não tem permissão para realizar esta ação.', 'inscricoes-pagas'),
                esc_html__('Erro', 'inscricoes-pagas'),
                ['response' => 403]
            );
            return;
        }

        // Obter product_id (via GET para links de download)
        $product_id = isset($_GET['product_id']) ? absint($_GET['product_id']) : 0;

        // Buscar pedidos
        $orders = wc_get_orders([
            'status' => 'completed',
            'limit' => -1,
        ]);

        // Exportar
        $meta_extractor = new Meta_Extractor();
        $exporter = new CSV_Exporter($meta_extractor);
        $exporter->export($orders, $product_id ?: null);
    }
}
