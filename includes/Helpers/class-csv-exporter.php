<?php
/**
 * Classe CSV_Exporter - Exportação para CSV.
 *
 * Classe dedicada para gerar arquivos CSV com suporte a caracteres
 * especiais, BOM para Excel e escape adequado.
 *
 * @package InscricoesPagas\Helpers
 * @since   2.0.0
 */

namespace InscricoesPagas\Helpers;

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Classe CSV_Exporter.
 *
 * @since 2.0.0
 */
class CSV_Exporter
{
    /**
     * Headers das colunas do CSV.
     *
     * @var array
     */
    private array $headers = [
        'ID do Pedido',
        'Data do Pedido',
        'Inscrito',
        'CBX OK',
        'Hotel',
        'Observação',
        'Caneca',
        'Nome Completo',
        'E-mail',
        'Celular',
        'Titulação',
        'Escola/Clube/Cidade',
        'Usuário Lichess',
        'ID CBX',
        'ID FIDE',
        'Deficiente (PCD)',
        'Cidade/Estado',
        'Instituição',
        'Naipe',
        'Categoria',
        'Data de Nascimento',
        'Nome Completo (Jogador 2)',
        'Data de Nascimento (Jogador 2)',
        'ID CBX (Jogador 2)',
        'ID FIDE (Jogador 2)',
    ];

    /**
     * Instância do extrator de metadados.
     *
     * @var Meta_Extractor
     */
    private Meta_Extractor $meta_extractor;

    /**
     * Construtor.
     *
     * @param Meta_Extractor $meta_extractor Instância do extrator.
     */
    public function __construct(Meta_Extractor $meta_extractor)
    {
        $this->meta_extractor = $meta_extractor;
    }

    /**
     * Exporta pedidos para CSV e envia para download.
     *
     * @param array    $orders     Array de pedidos WooCommerce.
     * @param int|null $product_id ID do produto para filtrar (opcional).
     *
     * @return void
     */
    public function export(array $orders, ?int $product_id = null): void
    {
        // Filtrar por produto se especificado
        if ($product_id) {
            $orders = $this->filter_orders_by_product($orders, $product_id);
        }

        // Configurar headers HTTP
        $this->set_download_headers();

        // Criar output stream
        $output = fopen('php://output', 'w');

        // Adicionar BOM para suporte a UTF-8 no Excel
        fprintf($output, "\xEF\xBB\xBF");

        // Escrever cabeçalhos
        fputcsv($output, $this->headers);

        // Escrever dados
        foreach ($orders as $order) {
            $this->write_order_rows($output, $order);
        }

        fclose($output);
        exit;
    }

    /**
     * Filtra pedidos por ID de produto.
     *
     * @param array $orders     Array de pedidos.
     * @param int   $product_id ID do produto.
     *
     * @return array Pedidos filtrados.
     */
    private function filter_orders_by_product(array $orders, int $product_id): array
    {
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
     * Define os headers HTTP para download do CSV.
     *
     * @return void
     */
    private function set_download_headers(): void
    {
        $filename = 'inscricoes_' . date('Y-m-d') . '.csv';

        // Limpar qualquer output anterior
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    /**
     * Escreve as linhas de um pedido no CSV.
     *
     * @param resource   $output Handle do arquivo de saída.
     * @param \WC_Order  $order  Pedido WooCommerce.
     *
     * @return void
     */
    private function write_order_rows($output, \WC_Order $order): void
    {
        $order_id = $order->get_id();
        $date_created = $order->get_date_created();
        $formatted_date = $date_created ? $date_created->date('d/m/Y H:i') : '';

        foreach ($order->get_items() as $item) {
            $meta = $this->meta_extractor->extract_from_item($item);

            $row = [
                $order_id,
                $formatted_date,
                $meta['inscrito'] == '1' ? 'Sim' : 'Não',
                $meta['cbx_arbitro'] == '1' ? 'Sim' : 'Não',
                $meta['hotel'] == '1' ? 'Sim' : 'Não',
                $meta['obs'] ?? '',
                $meta['qtd_caneca'],
                $meta['nome_completo'],
                $meta['email'],
                $meta['celular'],
                $meta['titulacao'],
                $meta['clube_cidade'],
                $meta['usuario_lichess'],
                $meta['id_cbx'],
                $meta['id_fide'],
                $meta['deficiente_pcd'],
                $meta['cidade_estado'],
                $meta['instituicao'],
                $meta['naipe'],
                $meta['categoria'],
                $meta['data_nascimento'],
                $meta['nome_jogador2'] ?? '',
                $meta['data_nascimento_jogador2'] ?? '',
                $meta['id_cbx_jogador2'] ?? '',
                $meta['id_fide_jogador2'] ?? '',
            ];

            fputcsv($output, $row);
        }
    }

    /**
     * Retorna os headers do CSV.
     *
     * @return array Array de headers.
     */
    public function get_headers(): array
    {
        return $this->headers;
    }

    /**
     * Define headers customizados.
     *
     * @param array $headers Novos headers.
     *
     * @return self
     */
    public function set_headers(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }
}
