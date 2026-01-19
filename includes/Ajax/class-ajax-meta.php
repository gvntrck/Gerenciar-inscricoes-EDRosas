<?php
/**
 * Classe Ajax_Meta - Handler para Metadados.
 *
 * Gerencia as requisições AJAX para obter e atualizar metadados de itens de pedido.
 *
 * @package InscricoesPagas\Ajax
 * @since   2.0.0
 */

namespace InscricoesPagas\Ajax;

use InscricoesPagas\Helpers\Meta_Extractor;

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Ajax_Meta.
 *
 * @since 2.0.0
 */
class Ajax_Meta extends Ajax_Base
{
    /**
     * Processa requisição para obter metadados.
     *
     * @return void
     */
    public function handle_get(): void
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

        global $wpdb;

        // Obter extrator de metadados
        $extractor = new Meta_Extractor();
        $desired_meta_keys = $extractor->get_detail_meta_keys();

        // Construir query
        $placeholders = implode(',', array_fill(0, count($desired_meta_keys), '%s'));
        $query_args = array_merge([$order_item_id], $desired_meta_keys);

        $metas = $wpdb->get_results($wpdb->prepare(
            "SELECT meta_key, meta_value 
             FROM {$wpdb->prefix}woocommerce_order_itemmeta 
             WHERE order_item_id = %d AND meta_key IN ($placeholders)",
            $query_args
        ), ARRAY_A);

        // Preparar dados
        $data = $extractor->prepare_detail_data($metas ?: []);

        // Obter método de pagamento
        $data['Método de Pagamento'] = $this->get_payment_method($order_item_id);

        wp_send_json_success($data);
    }

    /**
     * Processa requisição para atualizar metadados.
     *
     * @return void
     */
    public function handle_update(): void
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

        // Obter meta_data
        $meta_data = isset($_POST['meta_data']) ? $_POST['meta_data'] : [];

        if (!is_array($meta_data)) {
            wp_send_json_error([
                'message' => __('Dados de metadados inválidos.', 'inscricoes-pagas'),
                'code' => 'invalid_meta_data',
            ]);
            return;
        }

        global $wpdb;
        $table = $wpdb->prefix . 'woocommerce_order_itemmeta';
        $errors = [];

        foreach ($meta_data as $key => $value) {
            $key = sanitize_text_field($key);
            $value = sanitize_textarea_field($value);

            // Deletar todas as ocorrências existentes
            $wpdb->delete(
                $table,
                [
                    'order_item_id' => $order_item_id,
                    'meta_key' => $key,
                ],
                ['%d', '%s']
            );

            // Se valor não vazio, inserir novo registro
            if ($value !== '') {
                $result = $wpdb->insert(
                    $table,
                    [
                        'order_item_id' => $order_item_id,
                        'meta_key' => $key,
                        'meta_value' => $value,
                    ],
                    ['%d', '%s', '%s']
                );

                if ($result === false) {
                    $errors[] = $key;
                }
            }
        }

        if (empty($errors)) {
            wp_send_json_success([
                'message' => __('Metadados salvos com sucesso.', 'inscricoes-pagas'),
            ]);
        } else {
            wp_send_json_error([
                'message' => sprintf(
                    __('Erro ao salvar alguns metadados: %s', 'inscricoes-pagas'),
                    implode(', ', $errors)
                ),
                'code' => 'partial_update_failed',
            ]);
        }
    }

    /**
     * Método handle padrão (redireciona para handle_get).
     *
     * @return void
     */
    public function handle(): void
    {
        $this->handle_get();
    }

    /**
     * Obtém o método de pagamento de um item de pedido.
     *
     * @param int $order_item_id ID do item.
     *
     * @return string Método de pagamento ou 'N/A'.
     */
    private function get_payment_method(int $order_item_id): string
    {
        global $wpdb;

        $order_id = $wpdb->get_var($wpdb->prepare(
            "SELECT order_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_id = %d",
            $order_item_id
        ));

        if ($order_id) {
            $order = wc_get_order($order_id);
            if ($order) {
                $method = $order->get_payment_method_title();
                return $method ?: 'N/A';
            }
        }

        return 'N/A';
    }
}
