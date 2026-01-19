<?php
/**
 * Classe Admin - Funcionalidades Administrativas.
 *
 * Gerencia funcionalidades do painel administrativo do WordPress,
 * como páginas de configuração e hooks específicos do admin.
 *
 * @package InscricoesPagas\Admin
 * @since   2.0.0
 */

namespace InscricoesPagas\Admin;

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Admin.
 *
 * @since 2.0.0
 */
class Admin
{
    /**
     * Nome do plugin.
     *
     * @var string
     */
    private string $plugin_name;

    /**
     * Versão do plugin.
     *
     * @var string
     */
    private string $version;

    /**
     * Construtor.
     *
     * @param string $plugin_name Nome do plugin.
     * @param string $version     Versão do plugin.
     */
    public function __construct(string $plugin_name, string $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Adiciona página de configurações no menu admin.
     * 
     * @return void
     */
    public function add_settings_page(): void
    {
        add_submenu_page(
            'woocommerce',
            __('Inscrições Pagas', 'inscricoes-pagas'),
            __('Inscrições Pagas', 'inscricoes-pagas'),
            'manage_woocommerce',
            'inscricoes-pagas-settings',
            [$this, 'render_settings_page']
        );
    }

    /**
     * Renderiza a página de configurações.
     *
     * @return void
     */
    public function render_settings_page(): void
    {
        // Verificar permissões
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        // Salvar configurações se formulário foi enviado
        if (isset($_POST['inscricoes_pagas_save_settings'])) {
            $this->save_settings();
        }

        // Carregar template da página de configurações
        include INSCRICOES_PAGAS_PATH . 'templates/admin/settings.php';
    }

    /**
     * Registra as configurações do plugin.
     *
     * @return void
     */
    public function register_settings(): void
    {
        register_setting(
            'inscricoes_pagas_settings',
            'inscricoes_pagas_columns',
            [
                'type' => 'array',
                'sanitize_callback' => [$this, 'sanitize_columns_setting'],
                'default' => [],
            ]
        );

        register_setting(
            'inscricoes_pagas_settings',
            'inscricoes_pagas_per_page',
            [
                'type' => 'integer',
                'sanitize_callback' => 'absint',
                'default' => 50,
            ]
        );
    }

    /**
     * Sanitiza a configuração de colunas.
     *
     * @param array $value Valor a ser sanitizado.
     *
     * @return array Valor sanitizado.
     */
    public function sanitize_columns_setting($value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $sanitized = [];
        foreach ($value as $key => $enabled) {
            $sanitized[sanitize_key($key)] = (bool) $enabled;
        }

        return $sanitized;
    }

    /**
     * Salva as configurações enviadas pelo formulário.
     *
     * @return void
     */
    private function save_settings(): void
    {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['inscricoes_pagas_nonce'] ?? '', 'inscricoes_pagas_settings')) {
            add_settings_error(
                'inscricoes_pagas',
                'invalid_nonce',
                __('Erro de segurança. Por favor, tente novamente.', 'inscricoes-pagas'),
                'error'
            );
            return;
        }

        // Salvar configurações
        if (isset($_POST['inscricoes_pagas_columns'])) {
            $columns = $this->sanitize_columns_setting($_POST['inscricoes_pagas_columns']);
            update_option('inscricoes_pagas_columns', $columns);
        }

        if (isset($_POST['inscricoes_pagas_per_page'])) {
            $per_page = absint($_POST['inscricoes_pagas_per_page']);
            update_option('inscricoes_pagas_per_page', $per_page);
        }

        add_settings_error(
            'inscricoes_pagas',
            'settings_saved',
            __('Configurações salvas com sucesso.', 'inscricoes-pagas'),
            'success'
        );
    }

    /**
     * Adiciona link de configurações na página de plugins.
     *
     * @param array $links Links existentes.
     *
     * @return array Links modificados.
     */
    public function add_plugin_action_links(array $links): array
    {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            admin_url('admin.php?page=inscricoes-pagas-settings'),
            __('Configurações', 'inscricoes-pagas')
        );

        array_unshift($links, $settings_link);
        return $links;
    }
}
