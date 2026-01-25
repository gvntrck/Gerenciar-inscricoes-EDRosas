---
type: doc
name: project-overview
description: High-level overview of the project, its purpose, and key components
category: overview
generated: 2026-01-25
status: filled
scaffoldVersion: "2.0.0"
---

# Visão Geral do Projeto

## Sobre o Plugin

**Inscrições Pagas** é um plugin WordPress desenvolvido para gerenciar inscrições pagas em eventos de xadrez. Integrado ao WooCommerce, oferece uma interface interativa com tabela de inscritos, filtros avançados, ordenação, exportação CSV e edição inline de dados.

O plugin resolve o problema de gerenciamento manual de inscrições em eventos esportivos, permitindo que organizadores visualizem, filtrem e editem informações de participantes de forma eficiente diretamente no WordPress.

## Informações Rápidas

- **Diretório Raiz**: `c:\Users\Administrador\Documents\windsurf\71\Gerenciar-inscricoes-EDRosas`
- **Linguagem Principal**: PHP (WordPress Plugin)
- **Versão Atual**: 2.0.4
- **Requisitos**: PHP 7.4+, WordPress 5.0+, WooCommerce 4.0+
- **Arquivo Principal**: `inscricoes-pagas.php`
- **Namespace**: `InscricoesPagas`

## Pontos de Entrada

- **Plugin Bootstrap**: `@inscricoes-pagas.php:1` - Inicialização do plugin, verificação de requisitos e autoloader
- **Classe Principal**: `@includes/class-plugin.php:24` - Orquestrador que coordena todos os módulos
- **Shortcode**: `[inscricoes_pagas]` - Exibe tabela interativa de inscrições no frontend

## Exportações Principais

O plugin expõe funcionalidades via:
- **Shortcode**: `[inscricoes_pagas]` com parâmetro opcional `product_id`
- **Hooks AJAX**: 6 endpoints para operações CRUD
- **Classes Públicas**: Namespace `InscricoesPagas\*`

## Estrutura de Arquivos

- `inscricoes-pagas.php` — Arquivo principal do plugin com bootstrap e hooks de ativação
- `includes/` — Classes PHP organizadas por responsabilidade
  - `Admin/` — Funcionalidades do painel administrativo
  - `Ajax/` — Handlers de requisições AJAX
  - `Helpers/` — Utilitários (extração de metadados, exportação CSV)
  - `Public/` — Funcionalidades do frontend (shortcode, assets)
- `assets/` — Recursos estáticos (CSS, JavaScript, imagens)
- `templates/` — Templates PHP para renderização
- `novos-campos.md` — Documentação para adicionar novos campos

## Stack Tecnológica

**Backend**:
- PHP 7.4+ com tipagem estrita
- WordPress 5.0+ API (hooks, shortcodes, AJAX)
- WooCommerce para gerenciamento de pedidos
- Autoloader PSR-4 customizado

**Frontend**:
- JavaScript/jQuery para interatividade
- CSS customizado para tabela responsiva
- AJAX para operações sem reload

**Padrões**:
- Arquitetura orientada a objetos
- Princípios SOLID e Clean Code
- Padrão WordPress Plugin API
- Separação de responsabilidades (Admin/Public/Ajax)

## Frameworks e Bibliotecas

**Core**:
- **WordPress Plugin API** — Sistema de hooks e filtros
- **WooCommerce** — Integração com pedidos e metadados de produtos
- **jQuery** — Manipulação DOM e requisições AJAX

**Padrões Arquiteturais**:
- **Loader Pattern** — Gerenciamento centralizado de hooks
- **Extractor Pattern** — Extração de metadados WooCommerce
- **Template Pattern** — Renderização de views

## Ferramentas de Desenvolvimento

- **Autoloader Customizado** — Carregamento automático de classes
- **WordPress Coding Standards** — Convenções de código
- **Nonces** — Segurança em requisições AJAX
- **Sanitização/Escape** — Proteção contra XSS e SQL Injection

## Primeiros Passos

1. **Instalar dependências**: WooCommerce deve estar ativo
2. **Ativar o plugin**: Via painel WordPress em Plugins
3. **Usar o shortcode**: Adicione `[inscricoes_pagas]` em qualquer página
4. **Filtrar por produto**: Use `[inscricoes_pagas product_id="123"]`
5. **Consultar documentação**: Veja `novos-campos.md` para extensibilidade

## Próximos Passos

- **Arquitetura**: Consulte `architecture.md` para entender a estrutura de classes
- **Fluxo de Dados**: Veja `data-flow.md` para compreender como dados transitam
- **Desenvolvimento**: Leia `development-workflow.md` para contribuir
- **Glossário**: Acesse `glossary.md` para termos específicos do domínio
