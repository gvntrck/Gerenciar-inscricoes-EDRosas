<?php
/**
 * Classe Ajax_Base - Classe Base para Handlers AJAX.
 *
 * Fornece funcionalidades comuns para todos os handlers AJAX:
 * verificação de login, nonce, capabilities e ordem de item.
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
 * Classe abstrata Ajax_Base.
 *
 * @since 2.0.0
 */
abstract class Ajax_Base
{
    /**
     * Nome da action AJAX.
     *
     * @var string
     */
    protected string $action_name = '';

    /**
     * Capability necessária para executar a action.
     *
     * @var string
     */
    protected string $required_capability = 'manage_woocommerce';

    /**
     * Verifica se a requisição é válida.
     *
     * Realiza verificações de:
     * - Login do usuário
     * - Nonce de segurança
     * - Capabilities do usuário
     *
     * @return bool True se todas as verificações passarem.
     */
    protected function verify_request(): bool
    {
        // Verificar login
        if (!is_user_logged_in()) {
            wp_send_json_error([
                'message' => __('Você precisa estar logado para realizar esta ação.', 'inscricoes-pagas'),
                'code' => 'not_logged_in',
            ]);
            return false;
        }

        // Verificar nonce
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        if (!wp_verify_nonce($nonce, 'inscricoes_pagas_nonce')) {
            wp_send_json_error([
                'message' => __('Token de segurança inválido. Por favor, recarregue a página.', 'inscricoes-pagas'),
                'code' => 'invalid_nonce',
            ]);
            return false;
        }

        // Verificar capabilities
        if (!current_user_can($this->required_capability)) {
            wp_send_json_error([
                'message' => __('Você não tem permissão para realizar esta ação.', 'inscricoes-pagas'),
                'code' => 'insufficient_permissions',
            ]);
            return false;
        }

        return true;
    }

    /**
     * Obtém e valida o order_item_id da requisição.
     *
     * @return int Order item ID ou 0 se inválido.
     */
    protected function get_order_item_id(): int
    {
        return isset($_POST['order_item_id']) ? absint($_POST['order_item_id']) : 0;
    }

    /**
     * Verifica se o order_item_id é válido.
     *
     * @param int $order_item_id ID do item.
     *
     * @return bool True se válido.
     */
    protected function validate_order_item_id(int $order_item_id): bool
    {
        if ($order_item_id <= 0) {
            wp_send_json_error([
                'message' => __('ID do item de pedido inválido.', 'inscricoes-pagas'),
                'code' => 'invalid_order_item_id',
            ]);
            return false;
        }
        return true;
    }

    /**
     * Atualiza ou insere um metadado de item de pedido.
     *
     * @param int    $order_item_id ID do item.
     * @param string $meta_key      Chave do metadado.
     * @param string $meta_value    Valor do metadado.
     *
     * @return bool True se sucesso.
     */
    protected function upsert_order_item_meta(int $order_item_id, string $meta_key, string $meta_value): bool
    {
        global $wpdb;

        $table = $wpdb->prefix . 'woocommerce_order_itemmeta';

        // Verificar se existe
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT meta_id FROM {$table} WHERE order_item_id = %d AND meta_key = %s",
            $order_item_id,
            $meta_key
        ));

        if ($existing) {
            // Atualizar
            $result = $wpdb->update(
                $table,
                ['meta_value' => $meta_value],
                [
                    'order_item_id' => $order_item_id,
                    'meta_key' => $meta_key,
                ],
                ['%s'],
                ['%d', '%s']
            );
        } else {
            // Inserir
            $result = $wpdb->insert(
                $table,
                [
                    'order_item_id' => $order_item_id,
                    'meta_key' => $meta_key,
                    'meta_value' => $meta_value,
                ],
                ['%d', '%s', '%s']
            );
        }

        return $result !== false;
    }

    /**
     * Remove um metadado de item de pedido.
     *
     * @param int    $order_item_id ID do item.
     * @param string $meta_key      Chave do metadado.
     *
     * @return bool True se sucesso.
     */
    protected function delete_order_item_meta(int $order_item_id, string $meta_key): bool
    {
        global $wpdb;

        $result = $wpdb->delete(
            $wpdb->prefix . 'woocommerce_order_itemmeta',
            [
                'order_item_id' => $order_item_id,
                'meta_key' => $meta_key,
            ],
            ['%d', '%s']
        );

        return $result !== false;
    }

    /**
     * Método abstrato que deve ser implementado por cada handler.
     *
     * @return void
     */
    abstract public function handle(): void;
}
