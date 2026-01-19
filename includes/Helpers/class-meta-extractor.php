<?php
/**
 * Classe Meta_Extractor - Extração de Metadados WooCommerce.
 *
 * Centraliza toda a lógica de extração de metadados de itens de pedido
 * WooCommerce, evitando duplicação de código (DRY).
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
 * Classe Meta_Extractor.
 *
 * @since 2.0.0
 */
class Meta_Extractor
{
    /**
     * Mapeamento de chaves internas para meta_keys do WooCommerce.
     * Quando é um array, tenta cada chave em ordem até encontrar um valor.
     *
     * @var array
     */
    private array $meta_keys_map = [
        'nome_completo' => 'Nome completo',
        'email' => 'E-mail',
        'celular' => ['Celular - Incluir código do país', 'Celular - Incluir o DDD'],
        'titulacao' => 'Titulação:',
        'clube_cidade' => ['Escola/Clube/Cidade:', 'Clube/ Cidade:'],
        'usuario_lichess' => 'Usuário Lichess',
        'id_cbx' => 'ID CBX: (preencha com 0 caso não tenha)',
        'id_fide' => 'ID FIDE: (preencha com 0 caso não tenha)',
        'deficiente_pcd' => 'Deficiente (PCD)?',
        'cidade_estado' => 'Cidade/Estado',
        'instituicao' => 'Instituição',
        'naipe' => 'Naipe',
        'categoria' => 'Categoria',
        'opcao_torneio' => ['Opção meia (60+)', 'Opção'],
        'data_nascimento' => 'Data de nascimento',
        'qtd_caneca' => 'Quantidade de canecas',

        // Campos de controle do sistema
        'inscrito' => 'inscrito_arbitro',
        'cbx_arbitro' => 'cbx_arbitro',
        'obs' => 'obs_arbitro',
        'hotel' => 'hotel',

        // Campos para jogador 2 (duplas)
        'nome_jogador2' => 'Nome completo (Jogador 2)',
        'data_nascimento_jogador2' => 'Data de nascimento (Jogador 2)',
        'id_cbx_jogador2' => 'ID CBX (Jogador 2)',
        'id_fide_jogador2' => 'ID FIDE (Jogador 2)',

        // Equipes/Duplas
        'nome_equipe' => 'Nome da Equipe/Dupla',
        'nome_jogador1' => 'Nome (Jogador mesa 1)',
        'data_nascimento_jogador1' => 'Data de nascimento (Jogador 1)',
        'id_cbx_jogador1' => 'ID CBX (Jogador 1)',
        'id_fide_jogador1' => 'ID FIDE (Jogador 1)',
        'nome_jogador_mesa2' => 'Nome (Jogador mesa 2)',
    ];

    /**
     * Lista de meta_keys desejadas para consulta no modal de detalhes.
     *
     * @var array
     */
    private array $detail_meta_keys = [
        'Nome completo',
        'Data de nascimento',
        'E-mail',
        'Celular - Incluir código do país',
        'Celular - Incluir o DDD',
        'Clube/ Cidade:',
        'Escola/Clube/Cidade:',
        'Titulação:',
        'Usuário Lichess',
        'ID CBX: (preencha com 0 caso não tenha)',
        'ID FIDE: (preencha com 0 caso não tenha)',
        'Deficiente (PCD)?',
        'Nome da Equipe/Dupla',
        'Nome (Jogador mesa 1)',
        'Data de nascimento (Jogador 1)',
        'ID CBX (Jogador 1)',
        'ID FIDE (Jogador 1)',
        'Nome (Jogador mesa 2)',
        'Data de nascimento (Jogador 2)',
        'ID CBX (Jogador 2)',
        'ID FIDE (Jogador 2)',
        'hotel',
        'Naipe',
        'Categoria',
        'Opção meia (60+)',
        'Opção',
        'Cidade/Estado',
        'Instituição',
    ];

    /**
     * Extrai todos os metadados de um item de pedido.
     *
     * @param \WC_Order_Item_Product $item Item do pedido.
     *
     * @return array Array associativo com os metadados extraídos.
     */
    public function extract_from_item(\WC_Order_Item_Product $item): array
    {
        $data = [];

        foreach ($this->meta_keys_map as $key => $meta_key) {
            $value = $this->get_meta_value($item, $meta_key);

            // Tratamento especial para campos numéricos
            if ($key === 'qtd_caneca') {
                $value = $value ? intval($value) : 0;
            }

            // Tratamento para campos booleanos/checkbox
            if (in_array($key, ['inscrito', 'cbx_arbitro', 'hotel'], true)) {
                $value = $value === null ? '0' : $value;
            }

            // Tratamento para IDs (padrão 0)
            if (in_array($key, ['id_cbx', 'id_fide'], true)) {
                $value = $value ?: '0';
            }

            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * Extrai metadados para exibição na tabela (subset otimizado).
     *
     * @param \WC_Order_Item_Product $item Item do pedido.
     *
     * @return array Array com metadados para a tabela.
     */
    public function extract_for_table(\WC_Order_Item_Product $item): array
    {
        $table_keys = [
            'inscrito',
            'cbx_arbitro',
            'hotel',
            'obs',
            'qtd_caneca',
            'nome_completo',
            'email',
            'celular',
            'titulacao',
            'clube_cidade',
            'usuario_lichess',
            'id_cbx',
            'id_fide',
            'deficiente_pcd',
            'cidade_estado',
            'instituicao',
            'naipe',
            'categoria',
            'opcao_torneio',
        ];

        $full_data = $this->extract_from_item($item);

        return array_intersect_key($full_data, array_flip($table_keys));
    }

    /**
     * Obtém valor de um meta_key, suportando fallback para múltiplas chaves.
     *
     * @param \WC_Order_Item_Product $item     Item do pedido.
     * @param string|array           $meta_key Uma chave ou array de chaves.
     *
     * @return mixed|null Valor do metadado ou null se não encontrado.
     */
    private function get_meta_value(\WC_Order_Item_Product $item, $meta_key)
    {
        if (is_array($meta_key)) {
            foreach ($meta_key as $key) {
                $value = $item->get_meta($key);
                if (!empty($value)) {
                    return $value;
                }
            }
            return '';
        }

        return $item->get_meta($meta_key);
    }

    /**
     * Retorna a lista de meta_keys para consulta de detalhes.
     *
     * @return array Lista de meta_keys.
     */
    public function get_detail_meta_keys(): array
    {
        return $this->detail_meta_keys;
    }

    /**
     * Prepara dados de detalhes combinando metadados.
     *
     * @param array $raw_metas Metadados brutos do banco.
     *
     * @return array Dados formatados para exibição.
     */
    public function prepare_detail_data(array $raw_metas): array
    {
        $data = [
            'Nome completo' => '',
            'Data de nascimento' => '',
            'E-mail' => '',
            'Celular - Incluir código do país' => '',
            'Celular - Incluir o DDD' => '',
            'Clube/ Cidade:' => '',
            'Escola/Clube/Cidade:' => '',
            'Titulação:' => '',
            'Usuário Lichess' => '',
            'ID CBX: (preencha com 0 caso não tenha)' => '',
            'ID FIDE: (preencha com 0 caso não tenha)' => '',
            'Deficiente (PCD)?' => '',
            'Nome da Equipe/Dupla' => '',
            'Nome (Jogador mesa 1)' => '',
            'Data de nascimento (Jogador 1)' => '',
            'ID CBX (Jogador 1)' => '',
            'ID FIDE (Jogador 1)' => '',
            'Nome (Jogador mesa 2)' => '',
            'Data de nascimento (Jogador 2)' => '',
            'ID CBX (Jogador 2)' => '',
            'ID FIDE (Jogador 2)' => '',
            'hotel' => '',
            'Naipe' => '',
            'Categoria' => '',
            'Opção' => '',
            'Cidade/Estado' => '',
            'Instituição' => '',
        ];

        foreach ($raw_metas as $meta) {
            $key = $meta['meta_key'];
            $value = $meta['meta_value'];

            // Tratamento especial para opções do torneio
            if ($key === 'Opção meia (60+)' && !empty($value)) {
                $data['Opção'] = $value;
            } elseif ($key === 'Opção' && empty($data['Opção'])) {
                $data['Opção'] = $value;
            } elseif (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }
        }

        // Fallback para celular
        if (empty($data['Celular - Incluir código do país']) && !empty($data['Celular - Incluir o DDD'])) {
            $data['Celular - Incluir código do país'] = $data['Celular - Incluir o DDD'];
        }

        return $data;
    }
}
