=== Inscrições Pagas ===
Contributors: edrosas
Tags: woocommerce, inscricoes, eventos, xadrez, gerenciamento
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sistema de gerenciamento de inscrições pagas em eventos de xadrez integrado ao WooCommerce.

== Description ==

O plugin **Inscrições Pagas** permite gerenciar inscrições de eventos de xadrez vendidas através do WooCommerce. 

= Funcionalidades =

* Listagem de inscrições de pedidos WooCommerce concluídos
* Filtros por nome e status de inscrição
* Ordenação por qualquer coluna
* Checkboxes de controle: Inscrito, CBX OK, Hotel
* Campo de observações por inscrito
* Modal de detalhes com edição inline
* Exportação para CSV
* Configuração de colunas visíveis (salvo no localStorage)
* Interface responsiva com scroll horizontal

= Requisitos =

* WordPress 5.0 ou superior
* PHP 7.4 ou superior
* WooCommerce 4.0 ou superior

= Uso =

Use o shortcode `[inscricoes_pagas]` em qualquer página para exibir a tabela de inscrições.

Para filtrar por produto específico, use: `[inscricoes_pagas product_id="123"]`

== Installation ==

1. Faça upload da pasta `inscricoes-pagas` para o diretório `/wp-content/plugins/`
2. Ative o plugin através do menu "Plugins" no WordPress
3. Adicione o shortcode `[inscricoes_pagas]` em uma página

== Frequently Asked Questions ==

= O plugin funciona sem WooCommerce? =

Não. O plugin depende do WooCommerce para funcionar, pois utiliza os pedidos e metadados de itens de pedido.

= Os dados são perdidos ao desativar o plugin? =

Não. Os metadados dos pedidos são preservados mesmo após desativar ou desinstalar o plugin.

= Posso personalizar as colunas exibidas? =

Sim. Clique no botão "Configurar Colunas" para selecionar quais colunas deseja visualizar.

== Screenshots ==

1. Tabela de inscrições com filtros
2. Modal de detalhes com edição
3. Configuração de colunas

== Changelog ==

= 2.0.0 =
* Refatoração completa do código
* Arquitetura modular seguindo princípios SOLID
* CSS e JavaScript extraídos para arquivos separados
* Segurança aprimorada com nonce e verificação de capabilities
* Código organizado em namespaces

= 1.0.0 =
* Versão inicial (snippet)

== Upgrade Notice ==

= 2.0.0 =
Refatoração completa. Favor fazer backup antes de atualizar. Compatibilidade total com dados existentes.
