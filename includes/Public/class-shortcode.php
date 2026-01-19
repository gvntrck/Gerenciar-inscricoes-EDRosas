<?php
/**
 * Classe Shortcode - Handler do Shortcode [inscricoes_pagas].
 *
 * Responsável por processar o shortcode, buscar dados do WooCommerce
 * e renderizar a interface de inscrições.
 *
 * @package InscricoesPagas\PublicFacing
 * @since   2.0.0
 */

namespace InscricoesPagas\PublicFacing;

use InscricoesPagas\Helpers\Meta_Extractor;

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe Shortcode.
 *
 * @since 2.0.0
 */
class Shortcode
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
     * Instância do extrator de metadados.
     *
     * @var Meta_Extractor
     */
    private Meta_Extractor $meta_extractor;

    /**
     * Construtor.
     *
     * @param string         $plugin_name    Nome do plugin.
     * @param string         $version        Versão do plugin.
     * @param Meta_Extractor $meta_extractor Instância do extrator.
     */
    public function __construct(string $plugin_name, string $version, Meta_Extractor $meta_extractor)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->meta_extractor = $meta_extractor;
    }

    /**
     * Registra o shortcode.
     *
     * @return void
     */
    public function register(): void
    {
        add_shortcode('inscricoes_pagas', [$this, 'render']);
    }

    /**
     * Renderiza o shortcode.
     *
     * @param array $atts Atributos do shortcode.
     *
     * @return string HTML renderizado.
     */
    public function render($atts = []): string
    {
        // Verificar se usuário está logado
        if (!is_user_logged_in()) {
            return $this->render_login_message();
        }

        // Processar atributos
        $atts = shortcode_atts([
            'product_id' => 0,
        ], $atts, 'inscricoes_pagas');

        $product_id = absint($atts['product_id']);

        // Buscar pedidos
        $orders = $this->get_orders($product_id);

        // Calcular estatísticas
        $stats = $this->calculate_stats($orders);

        // Preparar dados para os templates
        $data = [
            'orders' => $orders,
            'stats' => $stats,
            'product_id' => $product_id,
            'extractor' => $this->meta_extractor,
            'version' => $this->version,
        ];

        // Renderizar templates
        ob_start();
        $this->render_template('shortcode/main', $data);
        return ob_get_clean();
    }

    /**
     * Renderiza mensagem para usuário não logado.
     *
     * @return string HTML da mensagem.
     */
    private function render_login_message(): string
    {
        $login_url = wp_login_url(get_permalink());

        return sprintf(
            '<div class="ip-alert ip-alert-warning">%s <a href="%s">%s</a>.</div>',
            esc_html__('Para acessar esta página, você precisa estar logado.', 'inscricoes-pagas'),
            esc_url($login_url),
            esc_html__('Clique aqui para fazer login', 'inscricoes-pagas')
        );
    }

    /**
     * Busca pedidos do WooCommerce.
     *
     * @param int $product_id ID do produto para filtrar (0 = todos).
     *
     * @return array Array de pedidos.
     */
    private function get_orders(int $product_id = 0): array
    {
        $orders = wc_get_orders([
            'status' => 'completed',
            'limit' => -1,
        ]);

        // Se não há filtro por produto, retorna todos
        if (!$product_id) {
            return $orders;
        }

        // Filtrar por produto
        $filtered = [];
        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                if ($item->get_product_id() == $product_id) {
                    $filtered[] = $order;
                    break;
                }
            }
        }

        return $filtered;
    }

    /**
     * Calcula estatísticas das inscrições.
     *
     * @param array $orders Pedidos para calcular.
     *
     * @return array Estatísticas calculadas.
     */
    private function calculate_stats(array $orders): array
    {
        $total_inscricoes = 0;
        $total_confirmadas = 0;

        foreach ($orders as $order) {
            foreach ($order->get_items() as $item) {
                $total_inscricoes++;

                $inscrito = $item->get_meta('inscrito_arbitro');
                if ($inscrito == '1') {
                    $total_confirmadas++;
                }
            }
        }

        return [
            'total' => $total_inscricoes,
            'confirmadas' => $total_confirmadas,
            'pendentes' => $total_inscricoes - $total_confirmadas,
        ];
    }

    /**
     * Renderiza um template.
     *
     * @param string $template Nome do template (sem extensão).
     * @param array  $data     Dados a serem extraídos no template.
     *
     * @return void
     */
    private function render_template(string $template, array $data = []): void
    {
        // Extrair variáveis para o template
        extract($data);

        $template_path = INSCRICOES_PAGAS_PATH . 'templates/' . $template . '.php';

        if (file_exists($template_path)) {
            include $template_path;
        }
    }
}
