<?php
/**
 * Classe principal do plugin.
 *
 * Esta classe é responsável por orquestrar todas as funcionalidades do plugin,
 * registrar hooks, carregar dependências e coordenar os diferentes módulos.
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
 * Classe Plugin - Orquestrador principal do plugin.
 *
 * @since 2.0.0
 */
class Plugin
{
    /**
     * Instância do loader que mantém os hooks.
     *
     * @var Loader
     */
    protected Loader $loader;

    /**
     * Identificador único do plugin.
     *
     * @var string
     */
    protected string $plugin_name;

    /**
     * Versão atual do plugin.
     *
     * @var string
     */
    protected string $version;

    /**
     * Inicializa o plugin.
     *
     * Define as propriedades principais, carrega dependências,
     * define hooks do admin e hooks públicos.
     */
    public function __construct()
    {
        $this->plugin_name = 'inscricoes-pagas';
        $this->version = INSCRICOES_PAGAS_VERSION;

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_ajax_hooks();
    }

    /**
     * Carrega as dependências necessárias para o plugin.
     *
     * Inclui os arquivos das classes utilizadas pelo plugin:
     * - Loader: Orquestra hooks
     * - Admin: Funcionalidades do painel administrativo
     * - PublicFacing: Funcionalidades do frontend
     * - Ajax: Handlers de requisições AJAX
     */
    private function load_dependencies(): void
    {
        // Loader para gerenciar hooks
        require_once INSCRICOES_PAGAS_PATH . 'includes/class-loader.php';

        // Helpers
        require_once INSCRICOES_PAGAS_PATH . 'includes/Helpers/class-meta-extractor.php';
        require_once INSCRICOES_PAGAS_PATH . 'includes/Helpers/class-csv-exporter.php';

        // Funcionalidades do admin
        require_once INSCRICOES_PAGAS_PATH . 'includes/Admin/class-admin.php';

        // Funcionalidades públicas
        require_once INSCRICOES_PAGAS_PATH . 'includes/Public/class-shortcode.php';
        require_once INSCRICOES_PAGAS_PATH . 'includes/Public/class-assets.php';

        // Handlers AJAX
        require_once INSCRICOES_PAGAS_PATH . 'includes/Ajax/class-ajax-base.php';
        require_once INSCRICOES_PAGAS_PATH . 'includes/Ajax/class-ajax-inscrito.php';
        require_once INSCRICOES_PAGAS_PATH . 'includes/Ajax/class-ajax-obs.php';
        require_once INSCRICOES_PAGAS_PATH . 'includes/Ajax/class-ajax-checkbox.php';
        require_once INSCRICOES_PAGAS_PATH . 'includes/Ajax/class-ajax-meta.php';
        require_once INSCRICOES_PAGAS_PATH . 'includes/Ajax/class-ajax-export.php';

        $this->loader = new Loader();
    }

    /**
     * Registra todos os hooks relacionados ao painel administrativo.
     */
    private function define_admin_hooks(): void
    {
        $admin = new Admin\Admin($this->plugin_name, $this->version);

        // Por enquanto, apenas prepara para futuras funcionalidades admin
        // $this->loader->add_action('admin_menu', $admin, 'add_settings_page');
        // $this->loader->add_action('admin_init', $admin, 'register_settings');
    }

    /**
     * Registra todos os hooks relacionados ao frontend público.
     */
    private function define_public_hooks(): void
    {
        // Assets (CSS e JS)
        $assets = new PublicFacing\Assets($this->plugin_name, $this->version);
        $this->loader->add_action('wp_enqueue_scripts', $assets, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $assets, 'enqueue_scripts');

        // Shortcode
        $meta_extractor = new Helpers\Meta_Extractor();
        $shortcode = new PublicFacing\Shortcode($this->plugin_name, $this->version, $meta_extractor);
        $this->loader->add_action('init', $shortcode, 'register');
    }

    /**
     * Registra todos os handlers AJAX.
     */
    private function define_ajax_hooks(): void
    {
        // Handler para atualizar status de inscrito
        $ajax_inscrito = new Ajax\Ajax_Inscrito();
        $this->loader->add_action('wp_ajax_ip_update_inscrito', $ajax_inscrito, 'handle');

        // Handler para atualizar observações
        $ajax_obs = new Ajax\Ajax_Obs();
        $this->loader->add_action('wp_ajax_ip_update_obs', $ajax_obs, 'handle');

        // Handler genérico para checkboxes (cbx_arbitro, hotel)
        $ajax_checkbox = new Ajax\Ajax_Checkbox();
        $this->loader->add_action('wp_ajax_ip_update_checkbox', $ajax_checkbox, 'handle');

        // Handler para obter/atualizar metadados
        $ajax_meta = new Ajax\Ajax_Meta();
        $this->loader->add_action('wp_ajax_ip_get_meta', $ajax_meta, 'handle_get');
        $this->loader->add_action('wp_ajax_ip_update_meta', $ajax_meta, 'handle_update');

        // Handler para exportação CSV
        $ajax_export = new Ajax\Ajax_Export();
        $this->loader->add_action('wp_ajax_ip_export_csv', $ajax_export, 'handle');
    }

    /**
     * Executa o plugin registrando todos os hooks com o WordPress.
     */
    public function run(): void
    {
        $this->loader->run();
    }

    /**
     * Retorna o nome do plugin.
     *
     * @return string Nome do plugin.
     */
    public function get_plugin_name(): string
    {
        return $this->plugin_name;
    }

    /**
     * Retorna o loader que gerencia os hooks.
     *
     * @return Loader Instância do loader.
     */
    public function get_loader(): Loader
    {
        return $this->loader;
    }

    /**
     * Retorna a versão do plugin.
     *
     * @return string Versão do plugin.
     */
    public function get_version(): string
    {
        return $this->version;
    }
}
