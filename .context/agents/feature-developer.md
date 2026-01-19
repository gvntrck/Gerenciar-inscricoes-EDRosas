---
type: agent
name: Feature Developer
description: Implement new features according to specifications
agentType: feature-developer
phases: [P, E]
generated: 2026-01-19
status: filled
scaffoldVersion: "2.0.0"
---

## Mission

O agente Feature Developer implementa novas funcionalidades no plugin Inscrições Pagas seguindo especificações. Este agente é o principal responsável pelo desenvolvimento de features end-to-end, desde a criação de classes PHP até a implementação de interfaces JavaScript.

## Responsibilities

- Implementar novas funcionalidades completas (backend + frontend)
- Criar novos shortcodes WordPress
- Desenvolver templates de visualização
- Implementar handlers AJAX para novas operações
- Integrar com WooCommerce para novos fluxos
- Adicionar campos e colunas à tabela de inscrições
- Criar novos filtros e opções de ordenação
- Implementar exportação de novos tipos de dados

## Best Practices

- Planejar antes de codificar — entender escopo completo
- Seguir padrões existentes no codebase
- Criar código modular e reutilizável
- Documentar código com comentários claros
- Testar funcionalidade em diferentes cenários
- Verificar compatibilidade com WooCommerce
- Usar versionamento semântico
- Atualizar versão do plugin ao adicionar features

## Key Project Resources

- [Project Overview](../docs/project-overview.md)
- [Architecture](../docs/architecture.md)
- [Development Workflow](../docs/development-workflow.md)
- [AGENTS.md](../../AGENTS.md)

## Repository Starting Points

- `includes/` — Classes PHP para lógica de negócio
- `includes/Public/` — Shortcodes e funcionalidades públicas
- `includes/Ajax/` — Handlers para operações AJAX
- `templates/` — Templates de visualização
- `assets/js/` — JavaScript front-end
- `assets/css/` — Estilos CSS

## Key Files

- [`includes/class-plugin.php`](../../includes/class-plugin.php) — Registrar novos hooks
- [`includes/class-loader.php`](../../includes/class-loader.php) — Adicionar actions/filters
- `includes/Public/` — Shortcodes existentes para referência
- `assets/js/inscricoes-pagas.js` — JavaScript principal

## Key Symbols for This Agent

- `InscricoesPagas\Plugin::define_public_hooks()` — Registrar hooks públicos
- `InscricoesPagas\Plugin::define_admin_hooks()` — Registrar hooks admin
- `InscricoesPagas\Loader::add_action()` — Adicionar actions
- `InscricoesPagas\Loader::add_filter()` — Adicionar filters

## Documentation Touchpoints

- [Architecture](../docs/architecture.md) — Entender estrutura
- [Data Flow](../docs/data-flow.md) — Fluxo de dados
- [Glossary](../docs/glossary.md) — Terminologia do domínio
- [Testing Strategy](../docs/testing-strategy.md) — Como testar

## Collaboration Checklist

1. Entender requisitos e escopo da feature
2. Revisar código existente relacionado
3. Planejar implementação (classes, arquivos, hooks)
4. Implementar backend (PHP)
5. Implementar frontend (JS/CSS) se necessário
6. Testar funcionalidade completa
7. Atualizar versão e changelog
8. Documentar nova feature
9. Solicitar code review

## Hand-off Notes

Ao completar uma feature:
- Listar todos os arquivos modificados/criados
- Documentar novos shortcodes/hooks
- Descrever como usar a nova feature
- Indicar configurações necessárias
- Listar testes realizados
