<?php
/**
 * Plugin Name:       Inscrições Pagas
 * Description:       Sistema de gerenciamento de inscrições pagas em eventos de xadrez. Exibe tabela interativa com filtros, ordenação, exportação CSV e edição de dados.
 * Version:           2.0.2
 * Author:            Giovani Tureck
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       inscricoes-pagas
 * Domain Path:       /languages
 * WC requires at least: 4.0
 * WC tested up to:   8.0
 *
 * @package InscricoesPagas
 */

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Versão atual do plugin.
 */
define('INSCRICOES_PAGAS_VERSION', '2.0.2');

/**
 * Caminho absoluto para o diretório do plugin.
 */
define('INSCRICOES_PAGAS_PATH', plugin_dir_path(__FILE__));

/**
 * URL para o diretório do plugin.
 */
define('INSCRICOES_PAGAS_URL', plugin_dir_url(__FILE__));

/**
 * Nome base do plugin.
 */
define('INSCRICOES_PAGAS_BASENAME', plugin_basename(__FILE__));

/**
 * Verificar requisitos mínimos antes de carregar o plugin.
 *
 * @return bool True se os requisitos forem atendidos.
 */
function inscricoes_pagas_check_requirements(): bool
{
    $errors = [];

    // Verificar versão do PHP
    if (version_compare(PHP_VERSION, '7.4', '<')) {
        $errors[] = sprintf(
            /* translators: %s: versão mínima do PHP */
            __('Inscrições Pagas requer PHP %s ou superior.', 'inscricoes-pagas'),
            '7.4'
        );
    }

    // Verificar versão do WordPress
    global $wp_version;
    if (version_compare($wp_version, '5.0', '<')) {
        $errors[] = sprintf(
            /* translators: %s: versão mínima do WordPress */
            __('Inscrições Pagas requer WordPress %s ou superior.', 'inscricoes-pagas'),
            '5.0'
        );
    }

    // Verificar se WooCommerce está ativo
    if (!class_exists('WooCommerce')) {
        $errors[] = __('Inscrições Pagas requer que o WooCommerce esteja instalado e ativo.', 'inscricoes-pagas');
    }

    // Se houver erros, exibir notificação no admin
    if (!empty($errors)) {
        add_action('admin_notices', function () use ($errors) {
            foreach ($errors as $error) {
                printf(
                    '<div class="notice notice-error"><p><strong>%s:</strong> %s</p></div>',
                    esc_html__('Inscrições Pagas', 'inscricoes-pagas'),
                    esc_html($error)
                );
            }
        });
        return false;
    }

    return true;
}

/**
 * Autoloader para as classes do plugin.
 *
 * @param string $class Nome completo da classe.
 */
function inscricoes_pagas_autoloader(string $class): void
{
    // Prefixo do namespace do plugin
    $prefix = 'InscricoesPagas\\';

    // Verificar se a classe usa o prefixo do namespace
    if (strpos($class, $prefix) !== 0) {
        return;
    }

    // Remover o prefixo do namespace
    $relative_class = substr($class, strlen($prefix));

    // Converter namespace para caminho de arquivo
    $file = INSCRICOES_PAGAS_PATH . 'includes/' . str_replace('\\', '/', $relative_class) . '.php';

    // Converter para formato de arquivo WordPress (class-nome-da-classe.php)
    $file = dirname($file) . '/class-' . strtolower(str_replace('_', '-', basename($file, '.php'))) . '.php';

    // Carregar o arquivo se existir
    if (file_exists($file)) {
        require_once $file;
    }
}

// Registrar o autoloader
spl_autoload_register('inscricoes_pagas_autoloader');

/**
 * Código executado durante a ativação do plugin.
 */
function inscricoes_pagas_activate(): void
{
    require_once INSCRICOES_PAGAS_PATH . 'includes/class-activator.php';
    InscricoesPagas\Activator::activate();
}

/**
 * Código executado durante a desativação do plugin.
 */
function inscricoes_pagas_deactivate(): void
{
    require_once INSCRICOES_PAGAS_PATH . 'includes/class-deactivator.php';
    InscricoesPagas\Deactivator::deactivate();
}

// Registrar hooks de ativação e desativação
register_activation_hook(__FILE__, 'inscricoes_pagas_activate');
register_deactivation_hook(__FILE__, 'inscricoes_pagas_deactivate');

/**
 * Inicializar o plugin.
 */
function inscricoes_pagas_init(): void
{
    // Verificar requisitos
    if (!inscricoes_pagas_check_requirements()) {
        return;
    }

    // Carregar traduções
    load_plugin_textdomain(
        'inscricoes-pagas',
        false,
        dirname(INSCRICOES_PAGAS_BASENAME) . '/languages'
    );

    // Inicializar a classe principal do plugin
    require_once INSCRICOES_PAGAS_PATH . 'includes/class-plugin.php';
    $plugin = new InscricoesPagas\Plugin();
    $plugin->run();
}

// Inicializar o plugin após todos os plugins serem carregados
add_action('plugins_loaded', 'inscricoes_pagas_init');

/**
 * Declarar compatibilidade com HPOS do WooCommerce.
 */
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});
