---
type: doc
name: project-overview
description: High-level overview of the project, its purpose, and key components
category: overview
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Project Overview

O **Inscrições Pagas** é um plugin WordPress desenvolvido para gerenciamento de inscrições pagas em eventos de xadrez. O plugin oferece uma tabela interativa com filtros, ordenação, exportação CSV e edição de dados, integrado ao WooCommerce para processamento de pagamentos.

> **Análise Detalhada**: Para contagens completas de símbolos, camadas de arquitetura e grafos de dependência, consulte [`codebase-map.json`](./codebase-map.json).

## Quick Facts

- **Raiz**: `c:\Users\Administrador\Documents\windsurf\insricoespagas`
- **Linguagens**: PHP (18 arquivos), JavaScript (1 arquivo), CSS (1 arquivo)
- **Versão**: 2.0.0
- **Requisitos**: PHP 7.4+, WordPress 5.0+, WooCommerce 4.0+
- **Entry Point**: `inscricoes-pagas.php`
- **Análise completa**: [`codebase-map.json`](./codebase-map.json)

## Entry Points

- [`inscricoes-pagas.php`](../../inscricoes-pagas.php) — Arquivo principal do plugin, inicialização e autoloader
- [`uninstall.php`](../../uninstall.php) — Script de desinstalação do plugin

## Key Exports

- Referência completa de exports disponível em [`codebase-map.json`](./codebase-map.json)
- Namespace principal: `InscricoesPagas`
- Classes principais: `Plugin`, `Activator`, `Deactivator`, `Loader`

## File Structure & Code Organization

```
inscricoes-pagas/
├── assets/                  # Assets front-end
│   ├── css/                 # Estilos CSS
│   └── js/                  # Scripts JavaScript
├── includes/                # Código PHP principal
│   ├── Admin/               # Funcionalidades administrativas
│   ├── Ajax/                # Handlers AJAX
│   ├── Helpers/             # Classes utilitárias
│   ├── Public/              # Funcionalidades públicas
│   ├── class-activator.php  # Lógica de ativação
│   ├── class-deactivator.php # Lógica de desativação
│   ├── class-loader.php     # Carregamento de hooks
│   └── class-plugin.php     # Classe principal do plugin
├── templates/               # Templates de visualização
├── languages/               # Arquivos de tradução
└── inscricoes-pagas.php     # Entry point principal
```

## Tech Stack & Infrastructure

- **Backend**: PHP 7.4+ com WordPress Plugin API
- **Frontend**: JavaScript vanilla, CSS
- **Integração**: WooCommerce para processamento de pagamentos
- **Banco de Dados**: MySQL via WordPress $wpdb
- **Padrões**: PSR-4 autoloading, WordPress Coding Standards

## Getting Started Checklist

1. Instale o WordPress 5.0+ e o WooCommerce 4.0+
2. Faça upload do plugin para `/wp-content/plugins/inscricoes-pagas/`
3. Ative o plugin no painel do WordPress
4. Configure os eventos e produtos WooCommerce associados
5. Consulte o [Development Workflow](./development-workflow.md) para tarefas diárias

## Next Steps

- Veja a [Arquitetura](./architecture.md) para entender a estrutura do sistema
- Consulte o [Data Flow](./data-flow.md) para entender o fluxo de dados
- Revise as configurações de [Segurança](./security.md)
