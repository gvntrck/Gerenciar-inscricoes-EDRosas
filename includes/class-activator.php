<?php
/**
 * Classe Activator - Ações de Ativação do Plugin.
 *
 * Esta classe contém todo o código executado durante a ativação do plugin,
 * como verificação de requisitos, criação de tabelas, definição de opções padrão.
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
 * Classe Activator.
 *
 * @since 2.0.0
 */
class Activator
{
    /**
     * Versão mínima do PHP requerida.
     */
    const MIN_PHP_VERSION = '7.4';

    /**
     * Versão mínima do WordPress requerida.
     */
    const MIN_WP_VERSION = '5.0';

    /**
     * Executa ações durante a ativação do plugin.
     *
     * @return void
     */
    public static function activate(): void
    {
        // Verificar requisitos
        self::check_requirements();

        // Definir opções padrão
        self::set_default_options();

        // Limpar cache de rewrite rules
        flush_rewrite_rules();

        // Registrar versão do plugin
        update_option('inscricoes_pagas_version', INSCRICOES_PAGAS_VERSION);

        // Registrar data de ativação
        if (!get_option('inscricoes_pagas_activated_at')) {
            update_option('inscricoes_pagas_activated_at', current_time('mysql'));
        }
    }

    /**
     * Verifica se os requisitos mínimos são atendidos.
     *
     * @return void
     */
    private static function check_requirements(): void
    {
        $errors = [];

        // Verificar versão do PHP
        if (version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<')) {
            $errors[] = sprintf(
                /* translators: 1: versão atual do PHP, 2: versão mínima requerida */
                __('Sua versão do PHP é %1$s, mas o plugin requer no mínimo %2$s.', 'inscricoes-pagas'),
                PHP_VERSION,
                self::MIN_PHP_VERSION
            );
        }

        // Verificar versão do WordPress
        global $wp_version;
        if (version_compare($wp_version, self::MIN_WP_VERSION, '<')) {
            $errors[] = sprintf(
                /* translators: 1: versão atual do WordPress, 2: versão mínima requerida */
                __('Sua versão do WordPress é %1$s, mas o plugin requer no mínimo %2$s.', 'inscricoes-pagas'),
                $wp_version,
                self::MIN_WP_VERSION
            );
        }

        // Verificar se WooCommerce está ativo
        if (!class_exists('WooCommerce')) {
            $errors[] = __('O WooCommerce precisa estar instalado e ativo para usar este plugin.', 'inscricoes-pagas');
        }

        // Se houver erros, impedir ativação
        if (!empty($errors)) {
            // Desativar o plugin
            deactivate_plugins(INSCRICOES_PAGAS_BASENAME);

            // Exibir erro e parar
            wp_die(
                '<h1>' . esc_html__('Erro ao ativar o plugin', 'inscricoes-pagas') . '</h1>' .
                '<p>' . implode('</p><p>', array_map('esc_html', $errors)) . '</p>' .
                '<p><a href="' . esc_url(admin_url('plugins.php')) . '">' .
                esc_html__('Voltar para a página de plugins', 'inscricoes-pagas') .
                '</a></p>',
                esc_html__('Erro de Ativação', 'inscricoes-pagas'),
                ['back_link' => true]
            );
        }
    }

    /**
     * Define as opções padrão do plugin.
     *
     * @return void
     */
    private static function set_default_options(): void
    {
        $default_options = [
            'inscricoes_pagas_columns' => [
                'inscrito' => true,
                'cbx_arbitro' => true,
                'hotel' => true,
                'data' => true,
                'obs' => true,
                'detalhes' => true,
                'qtd_caneca' => true,
                'nome_completo' => true,
                'email' => true,
                'celular' => true,
                'titulacao' => true,
                'clube_cidade' => true,
                'usuario_lichess' => true,
                'id_cbx' => true,
                'id_fide' => true,
                'deficiente_pcd' => true,
                'cidade_estado' => true,
                'instituicao' => true,
                'naipe' => true,
                'categoria' => true,
                'opcao_torneio' => true,
            ],
            'inscricoes_pagas_per_page' => 50,
        ];

        foreach ($default_options as $option_name => $option_value) {
            if (get_option($option_name) === false) {
                add_option($option_name, $option_value);
            }
        }
    }
}
