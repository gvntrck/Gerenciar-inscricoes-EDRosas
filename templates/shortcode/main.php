<?php
/**
 * Template principal do shortcode.
 *
 * @package InscricoesPagas
 * @since   2.0.0
 *
 * @var array               $orders     Pedidos WooCommerce.
 * @var array               $stats      Estatísticas das inscrições.
 * @var int                 $product_id ID do produto filtrado.
 * @var Meta_Extractor      $extractor  Instância do extrator de metadados.
 * @var string              $version    Versão do plugin.
 */

use InscricoesPagas\Helpers\Meta_Extractor;

// Se este arquivo for chamado diretamente, abortar.
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="ip-container">
    <?php // Área de filtros ?>
    <div class="ip-filters-container">
        <div class="ip-filters-title">
            <i class="fas fa-filter"></i> <?php esc_html_e('Filtros:', 'inscricoes-pagas'); ?>
        </div>
        <div class="ip-filters-group">
            <div class="ip-filter-item">
                <label class="ip-filter-label" for="ip-filtro-nome">
                    <?php esc_html_e('Nome:', 'inscricoes-pagas'); ?>
                </label>
                <input type="text" id="ip-filtro-nome" class="ip-filter-input" 
                       placeholder="<?php esc_attr_e('Filtrar por nome', 'inscricoes-pagas'); ?>">
            </div>
            <div class="ip-filter-item">
                <label class="ip-filter-label" for="ip-filtro-status">
                    <?php esc_html_e('Status:', 'inscricoes-pagas'); ?>
                </label>
                <select id="ip-filtro-status" class="ip-filter-input">
                    <option value=""><?php esc_html_e('Todos', 'inscricoes-pagas'); ?></option>
                    <option value="inscrito"><?php esc_html_e('Inscritos', 'inscricoes-pagas'); ?></option>
                    <option value="nao-inscrito"><?php esc_html_e('Não Inscritos', 'inscricoes-pagas'); ?></option>
                </select>
            </div>
        </div>
        
        <?php // Resumo estatístico ?>
        <div class="ip-summary-container">
            <div class="ip-summary-title">
                <i class="fas fa-chart-pie"></i> <?php esc_html_e('Resumo:', 'inscricoes-pagas'); ?>
            </div>
            <div class="ip-summary-item">
                <span><?php esc_html_e('Total de Inscrições Confirmadas:', 'inscricoes-pagas'); ?></span>
                <span class="ip-summary-value"><?php echo esc_html($stats['confirmadas']); ?></span>
            </div>
            <div style="margin-top: 8px;"></div>
            <div class="ip-summary-item">
                <span><?php esc_html_e('Total de Inscrições (Todas):', 'inscricoes-pagas'); ?></span>
                <span class="ip-summary-value"><?php echo esc_html($stats['total']); ?></span>
            </div>
        </div>
    </div>

    <?php // Barra de ações ?>
    <div class="ip-actions-bar">
        <a href="<?php echo esc_url(home_url('/area-do-arbitro/')); ?>" class="ip-btn ip-btn-default">
            <i class="fas fa-arrow-left"></i> <?php esc_html_e('Voltar', 'inscricoes-pagas'); ?>
        </a>
        <button id="ip-refresh-table" class="ip-btn ip-btn-default">
            <i class="fas fa-sync-alt"></i> <?php esc_html_e('Atualizar', 'inscricoes-pagas'); ?>
        </button>
        <a id="ip-export-csv" href="<?php echo esc_url(admin_url('admin-ajax.php?action=ip_export_csv' . ($product_id ? '&product_id=' . $product_id : ''))); ?>" 
           class="ip-btn ip-btn-success">
            <i class="fas fa-file-csv"></i> <?php esc_html_e('Exportar CSV', 'inscricoes-pagas'); ?>
        </a>
        <label class="ip-checkbox-label">
            <input type="checkbox" id="ip-filtro-ok"> <?php esc_html_e('CBX ok', 'inscricoes-pagas'); ?>
        </label>
        <label class="ip-checkbox-label">
            <input type="checkbox" id="ip-filtro-pendente"> <?php esc_html_e('CBX pendente', 'inscricoes-pagas'); ?>
        </label>
        <button id="ip-config-columns" class="ip-btn ip-btn-default">
            <i class="fas fa-columns"></i> <?php esc_html_e('Configurar Colunas', 'inscricoes-pagas'); ?>
        </button>
        <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="ip-btn ip-btn-danger ip-btn-logout">
            <i class="fas fa-sign-out-alt"></i> <?php esc_html_e('Sair', 'inscricoes-pagas'); ?>
        </a>
    </div>

    <?php // Container da tabela ?>
    <div class="ip-table-scroll-container">
        <div class="ip-double-scroll-top">
            <div></div>
        </div>
        <div class="ip-table-wrapper">
            <table id="ip-orders-table" class="ip-table">
                <thead>
                    <tr>
                        <th class="ip-resizable" data-column="inscrito"><?php esc_html_e('Inscrito', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="cbx_arbitro"><?php esc_html_e('CBX OK', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="hotel"><?php esc_html_e('Hotel', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="data"><?php esc_html_e('Data', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="obs"><?php esc_html_e('Obs', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="detalhes"><?php esc_html_e('Detalhes', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="qtd_caneca"><?php esc_html_e('Caneca', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="nome_completo"><?php esc_html_e('Nome Completo', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="email"><?php esc_html_e('E-mail', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="celular"><?php esc_html_e('Celular', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="titulacao"><?php esc_html_e('Titulação', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="clube_cidade"><?php esc_html_e('Escola/Clube/Cidade', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="usuario_lichess"><?php esc_html_e('Usuário Lichess', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="id_cbx"><?php esc_html_e('ID CBX', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="id_fide"><?php esc_html_e('ID FIDE', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="deficiente_pcd"><?php esc_html_e('Deficiente?', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="cidade_estado"><?php esc_html_e('Cidade/Estado', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="instituicao"><?php esc_html_e('Instituição', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="naipe"><?php esc_html_e('Naipe', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="categoria"><?php esc_html_e('Categoria', 'inscricoes-pagas'); ?></th>
                        <th class="ip-resizable" data-column="opcao_torneio"><?php esc_html_e('Opção', 'inscricoes-pagas'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)) : ?>
                        <?php foreach ($orders as $order) : ?>
                            <?php foreach ($order->get_items() as $item) : ?>
                                <?php
                                $order_item_id = $item->get_id();
                                $meta = $extractor->extract_for_table($item);
                                
                                // Data do pedido
                                $date_created = $order->get_date_created();
                                $formatted_date = $date_created ? $date_created->date('d/m/y H:i') : '';
                                
                                // Classes da linha
                                $row_classes = $meta['inscrito'] == '1' ? 'ip-highlight' : '';
                                ?>
                                <tr data-order-item-id="<?php echo esc_attr($order_item_id); ?>" class="<?php echo esc_attr($row_classes); ?>">
                                    <td data-column="inscrito">
                                        <input type="checkbox" class="ip-inscrito-checkbox" <?php checked($meta['inscrito'], '1'); ?>>
                                    </td>
                                    <td data-column="cbx_arbitro">
                                        <input type="checkbox" class="ip-generic-checkbox" data-type="cbx_arbitro" <?php checked($meta['cbx_arbitro'], '1'); ?>>
                                    </td>
                                    <td data-column="hotel">
                                        <input type="checkbox" class="ip-generic-checkbox" data-type="hotel" <?php checked($meta['hotel'], '1'); ?>>
                                    </td>
                                    <td data-column="data"><?php echo esc_html($formatted_date); ?></td>
                                    <td data-column="obs">
                                        <button class="ip-obs-button <?php echo !empty($meta['obs']) ? 'has-obs' : 'no-obs'; ?>" 
                                                data-order-item-id="<?php echo esc_attr($order_item_id); ?>" 
                                                data-obs="<?php echo esc_attr($meta['obs']); ?>"
                                                title="<?php echo esc_attr($meta['obs']); ?>">
                                            <?php esc_html_e('Obs', 'inscricoes-pagas'); ?>
                                        </button>
                                    </td>
                                    <td data-column="detalhes">
                                        <button class="ip-detalhes-button" 
                                                data-order-item-id="<?php echo esc_attr($order_item_id); ?>"
                                                title="<?php esc_attr_e('Ver Detalhes', 'inscricoes-pagas'); ?>">
                                            <?php esc_html_e('Detalhes', 'inscricoes-pagas'); ?>
                                        </button>
                                    </td>
                                    <td data-column="qtd_caneca"><?php echo esc_html($meta['qtd_caneca']); ?></td>
                                    <td data-column="nome_completo"><?php echo esc_html($meta['nome_completo']); ?></td>
                                    <td data-column="email"><?php echo esc_html($meta['email']); ?></td>
                                    <td data-column="celular"><?php echo esc_html($meta['celular']); ?></td>
                                    <td data-column="titulacao"><?php echo esc_html($meta['titulacao']); ?></td>
                                    <td data-column="clube_cidade"><?php echo esc_html($meta['clube_cidade']); ?></td>
                                    <td data-column="usuario_lichess"><?php echo esc_html($meta['usuario_lichess']); ?></td>
                                    <td data-column="id_cbx"><?php echo esc_html($meta['id_cbx']); ?></td>
                                    <td data-column="id_fide"><?php echo esc_html($meta['id_fide']); ?></td>
                                    <td data-column="deficiente_pcd"><?php echo esc_html($meta['deficiente_pcd']); ?></td>
                                    <td data-column="cidade_estado"><?php echo esc_html($meta['cidade_estado']); ?></td>
                                    <td data-column="instituicao"><?php echo esc_html($meta['instituicao']); ?></td>
                                    <td data-column="naipe"><?php echo esc_html($meta['naipe']); ?></td>
                                    <td data-column="categoria"><?php echo esc_html($meta['categoria']); ?></td>
                                    <td data-column="opcao_torneio"><?php echo esc_html($meta['opcao_torneio']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="21"><?php esc_html_e('Nenhum pedido concluído encontrado.', 'inscricoes-pagas'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php // Modal para Observação ?>
    <div id="ip-obs-modal" title="<?php esc_attr_e('Observação', 'inscricoes-pagas'); ?>" style="display:none;">
        <textarea id="ip-obs-textarea" rows="5" style="width:100%;"></textarea>
    </div>

    <?php // Modal para Configuração de Colunas ?>
    <div id="ip-columns-modal" title="<?php esc_attr_e('Configurar Colunas Visíveis', 'inscricoes-pagas'); ?>" style="display:none;">
        <p><?php esc_html_e('Selecione as colunas que deseja visualizar na tabela:', 'inscricoes-pagas'); ?></p>
        <div id="ip-columns-checkboxes" style="max-height: 400px; overflow-y: auto;">
            <!-- Checkboxes serão adicionados via JavaScript -->
        </div>
    </div>

    <?php // Modal para Detalhes ?>
    <div id="ip-detalhes-modal" title="<?php esc_attr_e('Detalhes do Pedido', 'inscricoes-pagas'); ?>" style="display:none;">
        <div id="ip-detalhes-content"><?php esc_html_e('Carregando detalhes...', 'inscricoes-pagas'); ?></div>
    </div>

    <?php // Versão ?>
    <div class="ip-version">
        <center>v<?php echo esc_html($version); ?></center>
    </div>
</div>
