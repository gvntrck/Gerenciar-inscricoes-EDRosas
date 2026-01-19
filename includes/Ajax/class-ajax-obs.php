<?php
/**
 * Classe Ajax_Obs - Handler para Observações.
 *
 * Gerencia as requisições AJAX para atualizar observações (obs_arbitro).
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
 * Classe Ajax_Obs.
 *
 * @since 2.0.0
 */
class Ajax_Obs extends Ajax_Base
{
    /**
     * Meta key para observações.
     */
    const META_KEY = 'obs_arbitro';

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

        // Obter e sanitizar observação
        $obs = isset($_POST['obs']) ? sanitize_textarea_field($_POST['obs']) : '';

        // Se observação vazia, remover o meta
        if (empty($obs)) {
            $result = $this->delete_order_item_meta($order_item_id, self::META_KEY);
        } else {
            $result = $this->upsert_order_item_meta($order_item_id, self::META_KEY, $obs);
        }

        if ($result) {
            wp_send_json_success([
                'message' => __('Observação salva com sucesso.', 'inscricoes-pagas'),
                'obs' => $obs,
                'hasObs' => !empty($obs),
            ]);
        } else {
            wp_send_json_error([
                'message' => __('Erro ao salvar observação.', 'inscricoes-pagas'),
                'code' => 'update_failed',
            ]);
        }
    }
}
