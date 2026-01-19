<?php
/**
 * Classe Ajax_Inscrito - Handler para Atualização de Status Inscrito.
 *
 * Gerencia as requisições AJAX para atualizar o meta 'inscrito_arbitro'.
 *
 * @package InscricoesPagas\Ajax
 * @since   2.0.0
 */

namespace InscricoesPagas\Ajax;

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Ajax_Inscrito.
 *
 * @since 2.0.0
 */
class Ajax_Inscrito extends Ajax_Base
{
    /**
     * Meta key para status de inscrito.
     */
    const META_KEY = 'inscrito_arbitro';

    /**
     * Processa a requisição AJAX.
     *
     * @return void
     */
    public function handle(): void
    {
        // Verificar requisição
        if (!$this->verify_request()) {
            return;
        }

        // Obter e validar order_item_id
        $order_item_id = $this->get_order_item_id();
        if (!$this->validate_order_item_id($order_item_id)) {
            return;
        }

        // Obter valor
        $value = isset($_POST['value']) ? sanitize_text_field($_POST['value']) : '0';

        // Validar valor (deve ser 0 ou 1)
        if (!in_array($value, ['0', '1'], true)) {
            $value = '0';
        }

        // Atualizar metadado
        $result = $this->upsert_order_item_meta($order_item_id, self::META_KEY, $value);

        if ($result) {
            wp_send_json_success([
                'message' => __('Status de inscrição atualizado com sucesso.', 'inscricoes-pagas'),
                'value' => $value,
            ]);
        } else {
            wp_send_json_error([
                'message' => __('Erro ao atualizar status de inscrição.', 'inscricoes-pagas'),
                'code' => 'update_failed',
            ]);
        }
    }
}
