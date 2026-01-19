/**
 * Inscrições Pagas - JavaScript Principal
 *
 * @package InscricoesPagas
 * @version 2.0.0
 */

(function ($) {
    'use strict';

    /**
     * Namespace do plugin
     */
    window.InscricoesPagas = window.InscricoesPagas || {};

    /**
     * Configuração e dados do servidor
     */
    const config = window.inscricoesPagasData || {
        ajaxUrl: '',
        nonce: '',
        i18n: {}
    };

    /**
     * Módulo de Utilitários
     */
    InscricoesPagas.Utils = {
        /**
         * Exibe notificação toast
         */
        showToast: function (message, isSuccess) {
            if (typeof Toastify === 'undefined') {
                console.log(message);
                return;
            }

            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                style: {
                    background: isSuccess ? "#28a745" : "#dc3545"
                }
            }).showToast();
        },

        /**
         * Copia texto para área de transferência
         */
        copyToClipboard: function (text) {
            const self = this;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text)
                    .then(function () {
                        self.showToast(config.i18n.copied || "Texto copiado!", true);
                    })
                    .catch(function () {
                        self.showToast(config.i18n.copyError || "Erro ao copiar texto", false);
                    });
            } else {
                try {
                    const temp = $('<textarea>').val(text).appendTo('body').select();
                    document.execCommand('copy');
                    temp.remove();
                    self.showToast(config.i18n.copied || "Texto copiado!", true);
                } catch (err) {
                    self.showToast(config.i18n.copyError || "Erro ao copiar texto", false);
                }
            }
        },

        /**
         * Faz requisição AJAX
         */
        ajax: function (action, data, onSuccess, onError) {
            const self = this;

            data.action = action;
            data.nonce = config.nonce;

            $.ajax({
                url: config.ajaxUrl,
                method: 'POST',
                data: data,
                success: function (response) {
                    if (response.success) {
                        if (onSuccess) onSuccess(response.data);
                    } else {
                        const message = response.data?.message || config.i18n.error;
                        self.showToast(message, false);
                        if (onError) onError(response.data);
                    }
                },
                error: function (xhr, status, error) {
                    self.showToast(config.i18n.error + ': ' + error, false);
                    console.error('AJAX Error:', error, xhr.responseText);
                    if (onError) onError({ message: error });
                }
            });
        }
    };

    /**
     * Módulo de Gerenciamento de Tabela
     */
    InscricoesPagas.Table = {
        /**
         * Inicializa a tabela
         */
        init: function () {
            this.initTableSorter();
            this.initResizable();
            this.initTooltips();
            this.initDoubleScroll();
            this.initFilters();
            this.initColumnSettings();
        },

        /**
         * Inicializa TableSorter
         */
        initTableSorter: function () {
            $("#ip-orders-table").tablesorter();
        },

        /**
         * Inicializa colunas redimensionáveis
         */
        initResizable: function () {
            $(".ip-resizable").resizable({
                handles: 'e',
                stop: function (event, ui) {
                    const columnWidths = {};
                    $('.ip-resizable').each(function () {
                        const column = $(this).data('column');
                        columnWidths[column] = $(this).width();
                    });
                    localStorage.setItem('ip_column_widths', JSON.stringify(columnWidths));
                }
            });

            // Restaurar larguras salvas
            const savedWidths = localStorage.getItem('ip_column_widths');
            if (savedWidths) {
                const widths = JSON.parse(savedWidths);
                Object.keys(widths).forEach(function (column) {
                    $('[data-column="' + column + '"]').width(widths[column]);
                });
            }
        },

        /**
         * Inicializa tooltips
         */
        initTooltips: function () {
            $('.ip-obs-button').tooltip({
                position: {
                    my: "center bottom-20",
                    at: "center top",
                    using: function (position, feedback) {
                        $(this).css(position);
                        $("<div>")
                            .addClass("arrow")
                            .addClass(feedback.vertical)
                            .addClass(feedback.horizontal)
                            .appendTo(this);
                    }
                },
                content: function () {
                    const obs = $(this).data('obs');
                    return obs ? obs : config.i18n.noObs || 'Sem observação';
                }
            });
        },

        /**
         * Inicializa scroll duplo
         */
        initDoubleScroll: function () {
            const $wrapper = $('.ip-table-wrapper');
            const $scrollTop = $('.ip-double-scroll-top');
            const $table = $('#ip-orders-table');

            // Sincronizar scrolls
            $wrapper.on('scroll', function () {
                $scrollTop.scrollLeft($(this).scrollLeft());
            });

            $scrollTop.on('scroll', function () {
                $wrapper.scrollLeft($(this).scrollLeft());
            });

            // Ajustar largura
            function adjustScrollWidth() {
                const tableWidth = $table.width();
                $scrollTop.find('> div').width(tableWidth);
            }

            adjustScrollWidth();
            $(window).on('resize', adjustScrollWidth);

            // Observer para mudanças na tabela
            if (typeof MutationObserver !== 'undefined' && $table.length) {
                const observer = new MutationObserver(adjustScrollWidth);
                observer.observe($table[0], { childList: true, subtree: true });
            }
        },

        /**
         * Inicializa filtros
         */
        initFilters: function () {
            const self = this;

            // Filtro por nome
            $('#ip-filtro-nome').on('keyup', function () {
                const valor = $(this).val().toLowerCase();
                $('#ip-orders-table tbody tr').each(function () {
                    const match = $(this).text().toLowerCase().indexOf(valor) > -1;
                    $(this).toggle(match);
                });
            });

            // Filtro por status
            $('#ip-filtro-status').on('change', function () {
                const valor = $(this).val();

                if (valor === '') {
                    $('#ip-orders-table tbody tr').show();
                } else if (valor === 'inscrito') {
                    $('#ip-orders-table tbody tr').each(function () {
                        $(this).toggle($(this).find('.ip-inscrito-checkbox').is(':checked'));
                    });
                } else if (valor === 'nao-inscrito') {
                    $('#ip-orders-table tbody tr').each(function () {
                        $(this).toggle(!$(this).find('.ip-inscrito-checkbox').is(':checked'));
                    });
                }
            });

            // Filtros CBX
            $('#ip-filtro-ok, #ip-filtro-pendente').on('change', function () {
                self.filterByCbx();
            });
        },

        /**
         * Filtra tabela por status CBX
         */
        filterByCbx: function () {
            const filtroOk = $('#ip-filtro-ok').is(':checked');
            const filtroPendente = $('#ip-filtro-pendente').is(':checked');

            $('#ip-orders-table tbody tr').each(function () {
                const row = $(this);
                const cbxValue = row.find('.ip-generic-checkbox[data-type="cbx_arbitro"]').is(':checked');

                if (!filtroOk && !filtroPendente) {
                    row.show();
                } else if (filtroOk && cbxValue) {
                    row.show();
                } else if (filtroPendente && !cbxValue) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        },

        /**
         * Inicializa configurações de colunas
         */
        initColumnSettings: function () {
            this.loadColumnSettings();
        },

        /**
         * Carrega configurações de colunas do localStorage
         */
        loadColumnSettings: function () {
            $('#ip-orders-table thead th').each(function () {
                const columnName = $(this).data('column');
                const isVisible = localStorage.getItem('ip_column_' + columnName) !== 'hidden';

                if (!isVisible) {
                    $(this).hide();
                    const columnIndex = $(this).index();
                    $('#ip-orders-table tbody tr').each(function () {
                        $(this).find('td:eq(' + columnIndex + ')').hide();
                    });
                }
            });
        },

        /**
         * Salva configurações de colunas
         */
        saveColumnSettings: function () {
            $('#ip-columns-checkboxes input[type="checkbox"]').each(function () {
                const columnName = $(this).attr('name').replace('column_', '');
                const isVisible = $(this).is(':checked');
                localStorage.setItem('ip_column_' + columnName, isVisible ? 'visible' : 'hidden');
            });
        },

        /**
         * Aplica configurações de colunas
         */
        applyColumnSettings: function () {
            $('#ip-orders-table thead th').each(function () {
                const columnName = $(this).data('column');
                const isVisible = localStorage.getItem('ip_column_' + columnName) !== 'hidden';

                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide();
                }

                const columnIndex = $(this).index();
                $('#ip-orders-table tbody tr').each(function () {
                    const td = $(this).find('td:eq(' + columnIndex + ')');
                    if (isVisible) {
                        td.show();
                    } else {
                        td.hide();
                    }
                });
            });
        }
    };

    /**
     * Módulo de Modais
     */
    InscricoesPagas.Modals = {
        /**
         * Inicializa todos os modais
         */
        init: function () {
            this.initObsModal();
            this.initColumnsModal();
            this.initDetalhesModal();
        },

        /**
         * Modal de Observações
         */
        initObsModal: function () {
            const self = this;

            $('#ip-obs-modal').dialog({
                autoOpen: false,
                modal: true,
                width: 500,
                buttons: {
                    "Salvar": function () {
                        self.saveObs();
                    },
                    "Cancelar": function () {
                        $(this).dialog("close");
                    }
                },
                close: function () {
                    $('#ip-obs-textarea').val('');
                }
            });

            // Abrir modal ao clicar no botão Obs
            $(document).on('click', '.ip-obs-button', function () {
                const button = $(this);
                const orderItemId = button.data('order-item-id');
                const currentObs = button.data('obs') || '';

                $('#ip-obs-textarea').val(currentObs);
                $('#ip-obs-modal').data('order-item-id', orderItemId);
                $('#ip-obs-modal').dialog('open');
            });
        },

        /**
         * Salva observação
         */
        saveObs: function () {
            const obsValue = $('#ip-obs-textarea').val().trim();
            const orderItemId = $('#ip-obs-modal').data('order-item-id');

            InscricoesPagas.Utils.ajax('ip_update_obs', {
                order_item_id: orderItemId,
                obs: obsValue
            }, function (data) {
                const obsButton = $('.ip-obs-button[data-order-item-id="' + orderItemId + '"]');
                obsButton.data('obs', obsValue);

                if (obsValue !== '') {
                    obsButton.removeClass('no-obs').addClass('has-obs');
                } else {
                    obsButton.removeClass('has-obs').addClass('no-obs');
                }

                InscricoesPagas.Utils.showToast(config.i18n.obsSaved || "Observação salva!", true);
                $('#ip-obs-modal').dialog("close");
            });
        },

        /**
         * Modal de Configuração de Colunas
         */
        initColumnsModal: function () {
            const self = this;

            $('#ip-columns-modal').dialog({
                autoOpen: false,
                modal: true,
                width: 400,
                buttons: {
                    "Aplicar": function () {
                        InscricoesPagas.Table.saveColumnSettings();
                        InscricoesPagas.Table.applyColumnSettings();
                        $(this).dialog("close");
                    },
                    "Cancelar": function () {
                        $(this).dialog("close");
                    }
                }
            });

            // Abrir modal
            $('#ip-config-columns').on('click', function () {
                self.buildColumnsCheckboxes();
                $('#ip-columns-modal').dialog('open');
            });
        },

        /**
         * Constrói checkboxes de colunas
         */
        buildColumnsCheckboxes: function () {
            const $container = $('#ip-columns-checkboxes');
            $container.empty();

            $('#ip-orders-table thead th').each(function () {
                const columnName = $(this).data('column');
                const columnText = $(this).text();
                const isVisible = localStorage.getItem('ip_column_' + columnName) !== 'hidden';

                const $checkbox = $('<div style="margin-bottom: 8px;">' +
                    '<label>' +
                    '<input type="checkbox" name="column_' + columnName + '" ' +
                    (isVisible ? 'checked' : '') + '> ' +
                    columnText +
                    '</label>' +
                    '</div>');

                $container.append($checkbox);
            });
        },

        /**
         * Modal de Detalhes
         */
        initDetalhesModal: function () {
            const self = this;

            $('#ip-detalhes-modal').dialog({
                autoOpen: false,
                modal: true,
                width: 600,
                buttons: [
                    {
                        text: "Editar",
                        class: "ip-edit-button",
                        click: function () {
                            self.enableEditMode();
                        }
                    },
                    {
                        text: "Salvar",
                        class: "ip-save-button",
                        click: function () {
                            self.saveMeta();
                        }
                    },
                    {
                        text: "Fechar",
                        click: function () {
                            $(this).dialog("close");
                        }
                    }
                ],
                close: function () {
                    $('#ip-detalhes-content').html(config.i18n.loadingDetails || 'Carregando detalhes...');
                }
            });

            // Abrir modal ao clicar em Detalhes
            $(document).on('click', '.ip-detalhes-button', function () {
                const orderItemId = $(this).data('order-item-id');
                self.loadDetails(orderItemId);
            });
        },

        /**
         * Carrega detalhes do item
         */
        loadDetails: function (orderItemId) {
            $('#ip-detalhes-content').html(config.i18n.loadingDetails || 'Carregando detalhes...');
            $('#ip-detalhes-modal').data('order-item-id', orderItemId);
            $('#ip-detalhes-modal').dialog('open');

            InscricoesPagas.Utils.ajax('ip_get_meta', {
                order_item_id: orderItemId
            }, function (data) {
                let html = '<div style="margin-bottom: 10px;"><label><input type="checkbox" id="ip-ocultar-vazios" checked> Ocultar campos vazios</label></div>';
                html += '<table style="width:100%; border-collapse: collapse;">';
                html += '<thead><tr><th style="border: 1px solid #dee2e6; padding: 5px 10px;">Chave</th><th style="border: 1px solid #dee2e6; padding: 5px 10px;">Valor</th></tr></thead><tbody>';

                $.each(data, function (key, value) {
                    const isEmpty = value === '' || value === 'N/A';
                    const rowStyle = isEmpty ? 'style="display:none;"' : '';
                    const copyIcon = '<i class="fas fa-copy ip-copy-icon" style="margin-right: 5px; cursor: pointer; color: #6c757d; font-size: 12px;" title="Copiar" data-copy-text="' + value + '"></i>';

                    html += '<tr data-meta-key="' + key + '" data-empty="' + isEmpty + '" ' + rowStyle + '>';
                    html += '<td style="border: 1px solid #dee2e6; padding: 5px 10px;">' + key + '</td>';
                    html += '<td class="ip-meta-value" style="border: 1px solid #dee2e6; padding: 5px 10px;">' + copyIcon + value + '</td>';
                    html += '</tr>';
                });

                html += '</tbody></table>';
                $('#ip-detalhes-content').html(html);

                // Toggle campos vazios
                $('#ip-ocultar-vazios').on('change', function () {
                    const checked = $(this).is(':checked');
                    if (checked) {
                        $('#ip-detalhes-content table tbody tr[data-empty="true"]').hide();
                    } else {
                        $('#ip-detalhes-content table tbody tr[data-empty="true"]').show();
                    }
                });

                // Copiar ao clicar
                $('#ip-detalhes-content').on('click', '.ip-copy-icon', function () {
                    const textToCopy = $(this).data('copy-text');
                    InscricoesPagas.Utils.copyToClipboard(textToCopy);
                });

            }, function () {
                $('#ip-detalhes-content').html(config.i18n.errorDetails || 'Erro ao carregar detalhes.');
            });
        },

        /**
         * Ativa modo de edição
         */
        enableEditMode: function () {
            $('#ip-detalhes-content table tbody tr:visible').each(function () {
                const tdValue = $(this).find('.ip-meta-value');
                const currentVal = tdValue.text().trim();

                if (!tdValue.find('input, textarea').length) {
                    const inputField = $('<input type="text" style="width: 95%">').val(currentVal);
                    tdValue.html(inputField);
                }
            });
        },

        /**
         * Salva metadados editados
         */
        saveMeta: function () {
            const orderItemId = $('#ip-detalhes-modal').data('order-item-id');
            const metaData = {};

            $('#ip-detalhes-content table tbody tr').each(function () {
                const metaKey = $(this).data('meta-key');
                const metaValueField = $(this).find('.ip-meta-value input');
                const newVal = metaValueField.length
                    ? metaValueField.val().trim()
                    : $(this).find('.ip-meta-value').text().trim();

                metaData[metaKey] = newVal;
            });

            InscricoesPagas.Utils.ajax('ip_update_meta', {
                order_item_id: orderItemId,
                meta_data: metaData
            }, function () {
                InscricoesPagas.Utils.showToast(config.i18n.metaSaved || "Metadados salvos!", true);
                $('#ip-detalhes-modal').dialog("close");
            });
        }
    };

    /**
     * Módulo de Handlers de Checkbox
     */
    InscricoesPagas.Checkboxes = {
        /**
         * Inicializa handlers
         */
        init: function () {
            this.initInscritoCheckbox();
            this.initGenericCheckbox();
        },

        /**
         * Checkbox de inscrito
         */
        initInscritoCheckbox: function () {
            $(document).on('change', '.ip-inscrito-checkbox', function () {
                const checkbox = $(this);
                const row = checkbox.closest('tr');
                const orderItemId = row.data('order-item-id');
                const value = checkbox.is(':checked') ? '1' : '0';

                row.toggleClass('ip-highlight', value === '1');

                InscricoesPagas.Utils.ajax('ip_update_inscrito', {
                    order_item_id: orderItemId,
                    value: value
                }, function () {
                    InscricoesPagas.Utils.showToast(config.i18n.success, true);
                }, function () {
                    row.toggleClass('ip-highlight', !checkbox.is(':checked'));
                    checkbox.prop('checked', !checkbox.is(':checked'));
                });
            });
        },

        /**
         * Checkboxes genéricos (cbx_arbitro, hotel)
         */
        initGenericCheckbox: function () {
            $(document).on('change', '.ip-generic-checkbox', function () {
                const checkbox = $(this);
                const row = checkbox.closest('tr');
                const orderItemId = row.data('order-item-id');
                const type = checkbox.data('type');
                const value = checkbox.is(':checked') ? '1' : '0';

                InscricoesPagas.Utils.ajax('ip_update_checkbox', {
                    order_item_id: orderItemId,
                    type: type,
                    value: value
                }, function () {
                    InscricoesPagas.Utils.showToast(config.i18n.success, true);
                }, function () {
                    checkbox.prop('checked', !checkbox.is(':checked'));
                });
            });
        }
    };

    /**
     * Inicialização
     */
    $(document).ready(function () {
        // Verificar se estamos na página correta
        if (!$('#ip-orders-table').length) {
            return;
        }

        // Inicializar módulos
        InscricoesPagas.Table.init();
        InscricoesPagas.Modals.init();
        InscricoesPagas.Checkboxes.init();

        // Botão de atualização
        $('#ip-refresh-table').on('click', function () {
            location.reload();
        });
    });

})(jQuery);
