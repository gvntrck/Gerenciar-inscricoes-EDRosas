<?php
/**
 * Classe Assets - Gerenciamento de CSS e JavaScript.
 *
 * Responsável por enfileirar (enqueue) estilos e scripts do plugin
 * apenas quando necessário.
 *
 * @package InscricoesPagas\PublicFacing
 * @since   2.0.0
 */

namespace InscricoesPagas\PublicFacing;

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Assets.
 *
 * @since 2.0.0
 */
class Assets
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
     * Verifica se a página atual contém o shortcode do plugin.
     *
     * @return bool True se deve carregar assets.
     */
    private function should_load_assets(): bool
    {
        global $post;

        if (!is_a($post, 'WP_Post')) {
            return false;
        }

        return has_shortcode($post->post_content, 'inscricoes_pagas');
    }

    /**
     * Enfileira estilos CSS do plugin.
     *
     * @return void
     */
    public function enqueue_styles(): void
    {
        if (!$this->should_load_assets()) {
            return;
        }

        // jQuery UI tema base
        wp_enqueue_style(
            'jquery-ui-base',
            'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css',
            [],
            '1.12.1'
        );

        // Font Awesome
        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
            [],
            '6.0.0'
        );

        // Toastify CSS
        wp_enqueue_style(
            'toastify-css',
            'https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css',
            [],
            '1.12.0'
        );

        // Estilos do plugin
        wp_enqueue_style(
            $this->plugin_name,
            INSCRICOES_PAGAS_URL . 'assets/css/inscricoes-pagas.css',
            ['jquery-ui-base'],
            $this->version
        );
    }

    /**
     * Enfileira scripts JavaScript do plugin.
     *
     * @return void
     */
    public function enqueue_scripts(): void
    {
        if (!$this->should_load_assets()) {
            return;
        }

        // jQuery UI
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_script('jquery-ui-tooltip');
        wp_enqueue_script('jquery-ui-resizable');

        // jQuery UI Touch Punch (suporte mobile)
        wp_enqueue_script(
            'jquery-ui-touch-punch',
            'https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js',
            ['jquery', 'jquery-ui-core'],
            '0.2.3',
            true
        );

        // TableSorter
        wp_enqueue_script(
            'jquery-tablesorter',
            'https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js',
            ['jquery'],
            '2.31.3',
            true
        );

        // Toastify
        wp_enqueue_script(
            'toastify-js',
            'https://cdn.jsdelivr.net/npm/toastify-js',
            [],
            '1.12.0',
            true
        );

        // Script principal do plugin
        wp_enqueue_script(
            $this->plugin_name,
            INSCRICOES_PAGAS_URL . 'assets/js/inscricoes-pagas.js',
            ['jquery', 'jquery-ui-dialog', 'jquery-ui-tooltip', 'jquery-tablesorter', 'toastify-js'],
            $this->version,
            true
        );

        // Passar dados para o JavaScript
        wp_localize_script($this->plugin_name, 'inscricoesPagasData', $this->get_script_data());
    }

    /**
     * Retorna dados a serem passados para o JavaScript.
     *
     * @return array Dados para wp_localize_script.
     */
    private function get_script_data(): array
    {
        return [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('inscricoes_pagas_nonce'),
            'i18n' => [
                'success' => __('Atualização feita com sucesso!', 'inscricoes-pagas'),
                'error' => __('Erro ao atualizar', 'inscricoes-pagas'),
                'obsSaved' => __('Observação salva com sucesso!', 'inscricoes-pagas'),
                'obsError' => __('Erro ao salvar observação', 'inscricoes-pagas'),
                'metaSaved' => __('Metadados salvos com sucesso!', 'inscricoes-pagas'),
                'metaError' => __('Erro ao salvar metadados', 'inscricoes-pagas'),
                'loadingDetails' => __('Carregando detalhes...', 'inscricoes-pagas'),
                'errorDetails' => __('Erro ao carregar detalhes.', 'inscricoes-pagas'),
                'noObs' => __('Sem observação', 'inscricoes-pagas'),
                'copied' => __('Texto copiado!', 'inscricoes-pagas'),
                'copyError' => __('Erro ao copiar texto', 'inscricoes-pagas'),
            ],
        ];
    }
}
