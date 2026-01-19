<?php
/**
 * Classe Ajax_Checkbox - Handler Genérico para Checkboxes.
 *
 * Gerencia as requisições AJAX para checkboxes genéricos como cbx_arbitro e hotel.
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
 * Classe Ajax_Checkbox.
 *
 * @since 2.0.0
 */
class Ajax_Checkbox extends Ajax_Base
{
    /**
     * Tipos de checkbox permitidos.
     *
     * @var array
     */
    private array $allowed_types = [
        'cbx_arbitro',
        'hotel',
    ];

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

        // Obter tipo do checkbox
        $type = isset($_POST['type']) ? sanitize_key($_POST['type']) : '';

        // Validar tipo
        if (!in_array($type, $this->allowed_types, true)) {
            wp_send_json_error([
                'message' => __('Tipo de checkbox inválido.', 'inscricoes-pagas'),
                'code' => 'invalid_type',
            ]);
            return;
        }

        // Obter valor
        $value = isset($_POST['value']) ? sanitize_text_field($_POST['value']) : '0';

        // Validar valor (deve ser 0 ou 1)
        if (!in_array($value, ['0', '1'], true)) {
            $value = '0';
        }

        // Atualizar metadado
        $result = $this->upsert_order_item_meta($order_item_id, $type, $value);

        if ($result) {
            wp_send_json_success([
                'message' => __('Atualização realizada com sucesso.', 'inscricoes-pagas'),
                'type' => $type,
                'value' => $value,
            ]);
        } else {
            wp_send_json_error([
                'message' => __('Erro ao atualizar.', 'inscricoes-pagas'),
                'code' => 'update_failed',
            ]);
        }
    }

    /**
     * Adiciona um tipo de checkbox permitido.
     *
     * @param string $type Tipo a adicionar.
     *
     * @return void
     */
    public function add_allowed_type(string $type): void
    {
        if (!in_array($type, $this->allowed_types, true)) {
            $this->allowed_types[] = sanitize_key($type);
        }
    }

    /**
     * Retorna os tipos de checkbox permitidos.
     *
     * @return array Lista de tipos.
     */
    public function get_allowed_types(): array
    {
        return $this->allowed_types;
    }
}
