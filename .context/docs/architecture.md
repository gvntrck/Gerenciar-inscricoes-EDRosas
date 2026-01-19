---
type: doc
name: architecture
description: System architecture, layers, patterns, and design decisions
category: architecture
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Architecture Notes

O plugin Inscrições Pagas segue a arquitetura padrão de plugins WordPress com separação clara de responsabilidades. A estrutura é modular, organizada em namespaces PHP com autoloading PSR-4.

## System Architecture Overview

O sistema adota uma arquitetura **monolítica modular**, típica de plugins WordPress, com:

- **Entry Point único** (`inscricoes-pagas.php`) que inicializa o autoloader e a classe principal
- **Padrão Loader** para registro centralizado de hooks e filtros WordPress
- **Separação Admin/Public** para funcionalidades do painel e front-end
- **Handlers AJAX** para operações assíncronas
- **Integração WooCommerce** para processamento de pagamentos

## Architectural Layers

- **Core**: Classes principais (`includes/class-*.php`) — Inicialização, ativação, loader
- **Admin**: Interface administrativa (`includes/Admin/`) — Páginas de configuração
- **Public**: Interface pública (`includes/Public/`) — Shortcodes, templates
- **Ajax**: Handlers AJAX (`includes/Ajax/`) — Operações assíncronas (6 handlers)
- **Helpers**: Utilitários (`includes/Helpers/`) — CSV export, funções auxiliares
- **Assets**: Front-end (`assets/`) — CSS e JavaScript

> Veja [`codebase-map.json`](./codebase-map.json) para contagens completas de símbolos e grafos de dependência.

## Detected Design Patterns

| Pattern | Confidence | Locations | Description |
|---------|------------|-----------|-------------|
| Singleton-like | 85% | `class-plugin.php` | Instância única do plugin via `plugins_loaded` |
| Loader/Registry | 90% | `class-loader.php` | Registro centralizado de actions e filters |
| Autoloader | 95% | `inscricoes-pagas.php` | PSR-4 style autoloading |
| Hook System | 100% | Todo o plugin | WordPress action/filter hooks |
| MVC-like | 70% | `templates/`, `includes/` | Separação de views e lógica |

## Entry Points

- [`inscricoes-pagas.php`](../../inscricoes-pagas.php) — Bootstrap do plugin, define constantes e autoloader
- [`uninstall.php`](../../uninstall.php) — Limpeza na desinstalação
- Hooks WordPress: `plugins_loaded`, `before_woocommerce_init`

## Top Directories Snapshot

| Diretório | Arquivos | Descrição |
|-----------|----------|-----------|
| `/includes` | 15 | Código PHP principal |
| `/includes/Ajax` | 6 | Handlers AJAX |
| `/includes/Helpers` | 2 | Classes utilitárias |
| `/assets` | 2 | CSS e JavaScript |
| `/templates` | 1 | Templates de visualização |

## External Service Dependencies

- **WooCommerce**: Obrigatório para funcionamento. Integração via classes WC e hooks
- **WordPress Database ($wpdb)**: Acesso ao banco de dados MySQL
- **WordPress REST API**: Potencial para futuras integrações

## Key Decisions & Trade-offs

1. **Autoloader customizado vs Composer**: Optou-se por autoloader simples para menor overhead
2. **Sem framework JS**: JavaScript vanilla para manter compatibilidade e performance
3. **Integração WooCommerce obrigatória**: O plugin depende do WooCommerce, limitando uso standalone mas garantindo robustez de pagamentos

## Related Resources

- [Project Overview](./project-overview.md)
- [Data Flow](./data-flow.md)
- [Codebase Map](./codebase-map.json)
